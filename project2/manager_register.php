<?php
/**
 * Manager Registration Page - TechHive Company
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Created: October 2025
 * Purpose: Register new HR manager accounts with validation
 */

session_start();
require_once 'settings.php';

$errors = [];
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    
    // Validation
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = "Username must be between 3 and 50 characters.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, and underscores.";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    if (empty($full_name)) {
        $errors[] = "Full name is required.";
    } elseif (strlen($full_name) > 100) {
        $errors[] = "Full name must be less than 100 characters.";
    }
    
    // Check if username or email already exists
    if (empty($errors)) {
        $check_sql = "SELECT username FROM managers WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Username or email already exists.";
        }
        mysqli_stmt_close($stmt);
    }
    
    // If no errors, insert new manager
    if (empty($errors)) {
        // Create managers table if it doesn't exist
        $create_table = "CREATE TABLE IF NOT EXISTS managers (
            manager_id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            full_name VARCHAR(100),
            created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            last_login TIMESTAMP NULL,
            login_attempts INT DEFAULT 0,
            account_locked BOOLEAN DEFAULT FALSE,
            locked_until TIMESTAMP NULL
        )";
        mysqli_query($conn, $create_table);
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new manager
        $insert_sql = "INSERT INTO managers (username, password, email, full_name) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $hashed_password, $email, $full_name);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Manager account created successfully! You can now <a href='manager_login.php'>login</a>.";
            // Clear form fields
            $username = $email = $full_name = '';
        } else {
            $errors[] = "Failed to create account. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Set page variables
$page_title = "Manager Registration | TechHive";
$page_description = "Register a new manager account for TechHive";

include 'header.inc';
include 'nav.inc';
?>

<main>
    <h1>Manager Registration</h1>
    
    <div class="registration-container">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="registration-form">
            <fieldset>
                <legend>Account Information</legend>
                
                <div class="form-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input type="text" id="username" name="username" required 
                           pattern="[a-zA-Z0-9_]{3,50}"
                           title="3-50 characters, letters, numbers, and underscores only"
                           value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                    <small>3-50 characters, letters, numbers, and underscores only</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="full_name">Full Name <span class="required">*</span></label>
                    <input type="text" id="full_name" name="full_name" required maxlength="100"
                           value="<?php echo isset($full_name) ? htmlspecialchars($full_name) : ''; ?>">
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Security</legend>
                
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password" required 
                           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                           title="Minimum 8 characters, at least one uppercase, lowercase, number and special character">
                    <small>Minimum 8 characters, must include uppercase, lowercase, number, and special character</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            </fieldset>
            
            <div class="form-actions">
                <button type="submit" class="btn-primary">Register</button>
                <a href="manager_login.php" class="btn-secondary">Back to Login</a>
            </div>
        </form>
        
        <div class="password-requirements">
            <h3>Password Requirements:</h3>
            <ul>
                <li>Minimum 8 characters</li>
                <li>At least one uppercase letter (A-Z)</li>
                <li>At least one lowercase letter (a-z)</li>
                <li>At least one number (0-9)</li>
                <li>At least one special character (@$!%*?&)</li>
            </ul>
        </div>
    </div>
</main>

<?php
mysqli_close($conn);
include 'footer.inc';
?>
