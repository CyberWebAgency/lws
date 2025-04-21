<?php
include 'includes/config.php';

try {
    // Test database connection
    if ($conn->ping()) {
        echo "Database connection is working!";
    } else {
        echo "Database connection failed!";
    }
    
    // Test if tables exist
    $tables = ['blogs', 'admin_users'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<br>Table '$table' exists!";
        } else {
            echo "<br>Table '$table' does not exist!";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 