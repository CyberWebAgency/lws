<?php
include '../includes/config.php';

// Destroy the session
session_destroy();

// Redirect to login page
redirect('login.php');
?> 