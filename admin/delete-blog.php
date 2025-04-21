<?php
session_start();
require_once '../backend/conn.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$blog_id = $_GET['id'] ?? 0;
if (!$blog_id) {
    header('Location: dashboard.php');
    exit();
}

// First, fetch the blog to get image paths
$stmt = $conn->prepare("SELECT thumbnail, image1, image2 FROM blogs WHERE id = ? AND author_id = ?");
$stmt->bind_param("ii", $blog_id, $_SESSION['admin_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: dashboard.php');
    exit();
}

$blog = $result->fetch_assoc();

// Delete associated images
$images = [$blog['thumbnail'], $blog['image1'], $blog['image2']];
foreach ($images as $image) {
    if ($image && file_exists($image)) {
        unlink($image);
    }
}

// Delete the blog post
$stmt = $conn->prepare("DELETE FROM blogs WHERE id = ? AND author_id = ?");
$stmt->bind_param("ii", $blog_id, $_SESSION['admin_id']);
$stmt->execute();

// Redirect back to dashboard with success message
$_SESSION['success_message'] = 'Blog post deleted successfully.';
header('Location: dashboard.php');
exit();
?> 