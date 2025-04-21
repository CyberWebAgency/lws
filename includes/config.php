<?php
$conn = new mysqli("localhost", "xwabppfj_tvl", "Kamal@121", "xwabppfj_lws");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>