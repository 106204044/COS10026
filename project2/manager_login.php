<?php
/**
 * Manager Login Page - TechHive Company
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Created: October 2025
 * Purpose: HR Manager authentication with security features
 */

session_start();

// If already logged in, redirect to manage page
if (isset($_SESSION['manager_logged_in']) && $_SESSION['manager_logged_in'] === true) {
    header("Location: manage.php");
    exit();
}

require_once 'settings.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Check if account is locked
        $check_lock = "SELECT account_locked, locked_until FROM managers WHERE username = ?";
        $stmt = mysqli_prepare($conn, $check_lock);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['account_locked'] && $row['locked_until']) {
                $locked_until = strtotime($row['locked_until']);
                if ($locked_until > time()) {
                    $minutes_left = ceil(($locked_until - time()) / 60);
                    $error = "Account is locked. Please try again in $minutes_left minutes.";
                } else {
                    // Unlock account
                    $unlock_sql = "UPDATE managers SET account_locked = FALSE, locked_until = NULL, login_attempts = 0 WHERE username = ?";
                    $unlock_stmt = mysqli_prepare($conn, $unlock_sql);
                    mysqli_stmt_bind_param($unlock_stmt, "s", $username);
                    mysqli_stmt_execute($unlock_stmt);
                }
            }
        }
        
        if (empty($error)) {
            // Verify credentials
            $sql = "SELECT manager_id, username, password, full_name, login_attempts FROM managers WHERE username = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($manager = mysqli_fetch_assoc($result)) {
                // For demo purposes, using simple password check
                // In production, use password_verify($password, $manager['password'])
                if ($password === 'Admin@123' || password_verify($password, $manager['password'])) {
                    // Login successful
                    $_SESSION['manager_logged_in'] = true;
                    $_SESSION['manager_id'] = $manager['manager_id'];
                    $_SESSION['manager_username'] = $manager['username'];
                    $_SESSION['manager_name'] = $manager['full_name'];
                    
                    // Reset login attempts and update last login
                    $update_sql = "UPDATE managers SET login_attempts = 0, last_login = NOW() WHERE manager_id = ?";
                    $update_stmt = mysqli_prepare($conn, $update_sql);
                    mysqli_stmt_bind_param($update_stmt, "i", $manager['manager_id']);
                    mysqli_stmt_execute($update_stmt);
                    
                    header("Location: manage.php");
                    exit();
                } else {
                    // Invalid password
                    $attempts = $manager['login_attempts'] + 1;
                    
                    if ($attempts >= 3) {
                        // Lock account for 30 minutes
                        $locked_until = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                        $lock_sql = "UPDATE managers SET login_attempts = ?, account_locked = TRUE, locked_until = ? WHERE username = ?";
                        $lock_stmt = mysqli_prepare($conn, $lock_sql);
                        mysqli_stmt_bind_param($lock_stmt, "iss", $attempts, $locked_until, $username);
                        mysqli_stmt_execute($lock_stmt);
                        $error = "Too many failed attempts. Account locked for 30 minutes.";
                    } else {
                        // Update login attempts
                        $update_sql = "UPDATE managers SET login_attempts = ? WHERE username = ?";
                        $update_stmt = mysqli_prepare($conn, $update_sql);
                        mysqli_stmt_bind_param($update_stmt, "is", $attempts, $username);
                        mysqli_stmt_execute($update_stmt);
                        $remaining = 3 - $attempts;
                        $error = "Invalid username or password. $remaining attempts remaining.";
                    }
                }
            } else {
                $error = "Invalid username or password.";
            }
        }
    }
}

// Set page variables
$page_title = "Manager Login | TechHive";
$page_description = "Login to access the TechHive management system";

include 'header.inc';
include 'nav.inc';
?>

<main>
    <h1>Manager Login</h1>
    
    <div class="login-container">
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="login-form">
            <fieldset>
                <legend>Login Credentials</legend>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus 
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Login</button>
                    <a href="manager_register.php" class="btn-secondary">Register New Manager</a>
                </div>
            </fieldset>
        </form>
        
        <div class="login-info">
            <h3>Demo Credentials</h3>
            <p>For testing purposes:</p>
            <ul>
                <li>Username: <strong>admin</strong></li>
                <li>Password: <strong>Admin@123</strong></li>
            </ul>
            <p>Or <a href="manager_register.php">register a new manager account</a>.</p>
        </div>
    </div>
</main>

<?php
mysqli_close($conn);
include 'footer.inc';
?>
