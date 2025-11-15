<?php
/**
 * Database Connection Settings
 * TechHive Company
 * Authors: Nguyen Duy Anh (SWH03179), Tran Anh Tuan (SWD00440)
 * Purpose: Store database connection credentials
 * Security: This file should be protected and not accessible via web
 */

// Database connection parameters
$host = "localhost";           // Database host
$user = "root";                // Database username - change for production
$pwd = "";                     // Database password - change for production
$database = "techhive_db";     // Database name

// Create connection
$conn = @mysqli_connect($host, $user, $pwd, $database);

// Check connection
if (!$conn) {
    // In production, log this error instead of displaying
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8 for proper character encoding
mysqli_set_charset($conn, "utf8mb4");

// Start session for user management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
