<?php
$conn = new mysqli("localhost", "root", "", "lawgical_station");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
