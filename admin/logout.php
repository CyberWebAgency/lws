<?php
include '../backend/conn.php';

// Destroy the session
session_destroy();

// Redirect to login page
redirect('login.php');
?> 