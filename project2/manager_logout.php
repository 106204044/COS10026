<?php
/**
 * Manager Logout - TechHive Company
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Created: October 2025
 * Purpose: Logout functionality for managers
 */

session_start();

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
header("Location: manager_login.php?logout=success");
exit();
?>
