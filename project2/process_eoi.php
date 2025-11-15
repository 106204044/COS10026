<?php
/**
 * Process EOI (Expression of Interest) Form
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Created: October 2025
 * Purpose: Process application form with server-side validation and database storage
 */

// Include database connection
require_once 'settings.php';

// Check if form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: apply.php");
    exit();
}

// Initialize errors array
$errors = [];

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate date format
function validate_date($date) {
    $pattern = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/';
    if (!preg_match($pattern, $date)) {
        return false;
    }
    
    // Check if date is valid
    $parts = explode('/', $date);
    if (count($parts) == 3) {
        return checkdate($parts[1], $parts[0], $parts[2]);
    }
    return false;
}

// Function to validate postcode matches state
function validate_postcode_state($postcode, $state) {
    $postcode_ranges = [
        'VIC' => ['3000', '3999'],
        'NSW' => ['2000', '2999'],
        'QLD' => ['4000', '4999'],
        'NT' => ['0800', '0999'],
        'WA' => ['6000', '6999'],
        'SA' => ['5000', '5999'],
        'TAS' => ['7000', '7999'],
        'ACT' => ['2600', '2699']
    ];
    
    if (isset($postcode_ranges[$state])) {
        $pc = intval($postcode);
        $min = intval($postcode_ranges[$state][0]);
        $max = intval($postcode_ranges[$state][1]);
        return ($pc >= $min && $pc <= $max);
    }
    return false;
}

// Validate and sanitize form inputs
$job_reference = isset($_POST['job_reference']) ? sanitize_input($_POST['job_reference']) : '';
$first_name = isset($_POST['first_name']) ? sanitize_input($_POST['first_name']) : '';
$last_name = isset($_POST['last_name']) ? sanitize_input($_POST['last_name']) : '';
$date_of_birth = isset($_POST['date_of_birth']) ? sanitize_input($_POST['date_of_birth']) : '';
$gender = isset($_POST['gender']) ? sanitize_input($_POST['gender']) : '';
$street_address = isset($_POST['street_address']) ? sanitize_input($_POST['street_address']) : '';
$suburb = isset($_POST['suburb']) ? sanitize_input($_POST['suburb']) : '';
$state = isset($_POST['state']) ? sanitize_input($_POST['state']) : '';
$postcode = isset($_POST['postcode']) ? sanitize_input($_POST['postcode']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
$skills = isset($_POST['skills']) ? $_POST['skills'] : [];
$other_skills = isset($_POST['other_skills']) ? sanitize_input($_POST['other_skills']) : '';

// Validation rules
if (empty($job_reference)) {
    $errors[] = "Job reference number is required.";
}

if (empty($first_name)) {
    $errors[] = "First name is required.";
} elseif (!preg_match("/^[a-zA-Z\s\-']{1,20}$/", $first_name)) {
    $errors[] = "First name must be maximum 20 alphabetic characters.";
}

if (empty($last_name)) {
    $errors[] = "Last name is required.";
} elseif (!preg_match("/^[a-zA-Z\s\-']{1,20}$/", $last_name)) {
    $errors[] = "Last name must be maximum 20 alphabetic characters.";
}

if (empty($date_of_birth)) {
    $errors[] = "Date of birth is required.";
} elseif (!validate_date($date_of_birth)) {
    $errors[] = "Date of birth must be in DD/MM/YYYY format.";
} else {
    // Convert to MySQL date format
    $parts = explode('/', $date_of_birth);
    $mysql_date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
    
    // Check age (must be at least 15 and not more than 80)
    $dob = new DateTime($mysql_date);
    $now = new DateTime();
    $age = $now->diff($dob)->y;
    
    if ($age < 15 || $age > 80) {
        $errors[] = "Age must be between 15 and 80 years.";
    }
}

if (empty($gender)) {
    $errors[] = "Gender is required.";
} elseif (!in_array($gender, ['male', 'female', 'non-binary', 'prefer-not-to-say'])) {
    $errors[] = "Invalid gender selection.";
}

if (empty($street_address)) {
    $errors[] = "Street address is required.";
} elseif (strlen($street_address) > 40) {
    $errors[] = "Street address must be maximum 40 characters.";
}

if (empty($suburb)) {
    $errors[] = "Suburb/town is required.";
} elseif (strlen($suburb) > 40) {
    $errors[] = "Suburb/town must be maximum 40 characters.";
}

if (empty($state)) {
    $errors[] = "State is required.";
} elseif (!in_array($state, ['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'])) {
    $errors[] = "Invalid state selection.";
}

if (empty($postcode)) {
    $errors[] = "Postcode is required.";
} elseif (!preg_match("/^\d{4}$/", $postcode)) {
    $errors[] = "Postcode must be exactly 4 digits.";
} elseif (!empty($state) && !validate_postcode_state($postcode, $state)) {
    $errors[] = "Postcode does not match selected state.";
}

if (empty($email)) {
    $errors[] = "Email address is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email address format.";
}

if (empty($phone)) {
    $errors[] = "Phone number is required.";
} else {
    // Remove spaces for validation
    $phone_digits = str_replace(' ', '', $phone);
    if (!preg_match("/^\d{8,12}$/", $phone_digits)) {
        $errors[] = "Phone number must be 8 to 12 digits.";
    }
}

// Check if at least one skill is selected
if (empty($skills)) {
    $errors[] = "At least one technical skill must be selected.";
}

// Check if other skills is required when certain checkbox is selected
if (!empty($skills) && in_array('other', $skills) && empty($other_skills)) {
    $errors[] = "Please describe your other skills.";
}

// If there are errors, display them
if (!empty($errors)) {
    $page_title = "Application Error | TechHive";
    $page_description = "Error processing job application";
    include 'header.inc';
    include 'nav.inc';
    ?>
    <main>
        <h1>Application Error</h1>
        <div class="error-container">
            <h2>Please correct the following errors:</h2>
            <ul class="error-list">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <p><a href="apply.php" class="btn-primary">Return to Application Form</a></p>
        </div>
    </main>
    <?php
    include 'footer.inc';
    exit();
}

// If validation passes, insert into database
// Create EOI table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    job_reference VARCHAR(10) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    date_of_birth DATE,
    gender ENUM('male', 'female', 'non-binary', 'prefer-not-to-say'),
    street_address VARCHAR(40) NOT NULL,
    suburb VARCHAR(40) NOT NULL,
    state ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
    postcode CHAR(4) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(12) NOT NULL,
    skill1 VARCHAR(50),
    skill2 VARCHAR(50),
    skill3 VARCHAR(50),
    skill4 VARCHAR(50),
    skill5 VARCHAR(50),
    skill6 VARCHAR(50),
    skill7 VARCHAR(50),
    skill8 VARCHAR(50),
    other_skills TEXT,
    status ENUM('New', 'Current', 'Final') DEFAULT 'New',
    submitted_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_job_ref (job_reference),
    INDEX idx_status (status),
    INDEX idx_name (first_name, last_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

mysqli_query($conn, $create_table_sql);

// Prepare skills for database insertion
$skill_fields = [];
for ($i = 0; $i < 8; $i++) {
    $skill_fields[$i] = isset($skills[$i]) ? $skills[$i] : '';
}

// Prepare and execute insert statement
$insert_sql = "INSERT INTO eoi (
    job_reference, first_name, last_name, date_of_birth, gender,
    street_address, suburb, state, postcode, email, phone,
    skill1, skill2, skill3, skill4, skill5, skill6, skill7, skill8,
    other_skills, status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New')";

$stmt = mysqli_prepare($conn, $insert_sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssssssssssssssssssss",
        $job_reference, $first_name, $last_name, $mysql_date, $gender,
        $street_address, $suburb, $state, $postcode, $email, $phone,
        $skill_fields[0], $skill_fields[1], $skill_fields[2], $skill_fields[3],
        $skill_fields[4], $skill_fields[5], $skill_fields[6], $skill_fields[7],
        $other_skills
    );
    
    if (mysqli_stmt_execute($stmt)) {
        $eoi_number = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);
        
        // Display success page
        $page_title = "Application Submitted | TechHive";
        $page_description = "Your job application has been successfully submitted";
        include 'header.inc';
        include 'nav.inc';
        ?>
        <main>
            <h1>Application Submitted Successfully</h1>
            <div class="success-container">
                <h2>Thank you for your application!</h2>
                <p>Your Expression of Interest has been received and recorded.</p>
                <div class="confirmation-details">
                    <p><strong>Your EOI Number:</strong> <?php echo $eoi_number; ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></p>
                    <p><strong>Position Applied:</strong> <?php echo htmlspecialchars($job_reference); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                </div>
                <p>We will review your application and contact you if you are selected for an interview.</p>
                <div class="action-buttons">
                    <a href="index.php" class="btn-primary">Return to Home</a>
                    <a href="jobs.php" class="btn-secondary">View More Jobs</a>
                </div>
            </div>
        </main>
        <?php
        include 'footer.inc';
    } else {
        // Database error
        $page_title = "Application Error | TechHive";
        $page_description = "Error processing job application";
        include 'header.inc';
        include 'nav.inc';
        ?>
        <main>
            <h1>Application Error</h1>
            <div class="error-container">
                <h2>Database Error</h2>
                <p>There was an error processing your application. Please try again later.</p>
                <p>If the problem persists, please contact us at <a href="mailto:careers@techhive.com">careers@techhive.com</a></p>
                <p><a href="apply.php" class="btn-primary">Return to Application Form</a></p>
            </div>
        </main>
        <?php
        include 'footer.inc';
    }
} else {
    // Prepare statement error
    die("Prepare statement failed: " . mysqli_error($conn));
}

// Close database connection
mysqli_close($conn);
?>
