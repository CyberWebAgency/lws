<?php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'xwabppfj_lws');
define('DB_PASS', 'your_password_here');
define('DB_NAME', 'xwabppfj_lws');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}
?> 