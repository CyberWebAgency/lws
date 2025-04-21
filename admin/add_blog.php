<?php
session_start();
require_once '../backend/conn.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $slug = strtolower(str_replace(' ', '-', preg_replace('/[^a-zA-Z0-9\s]/', '', $title)));
    $content = $_POST['content'];
    $meta_description = $_POST['meta_description'];
    $status = $_POST['status'];
    
    // Handle image uploads
    $thumbnail = null;
    $image1 = null;
    $image2 = null;
    $image3 = null;
    
    // Function to handle image upload
    function handleImageUpload($file) {
        if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
            // Check if file is an image
            $check = getimagesize($file['tmp_name']);
            if ($check !== false) {
                return file_get_contents($file['tmp_name']);
            }
        }
        return null;
    }
    
    // Process each image
    $thumbnail = handleImageUpload($_FILES['thumbnail']);
    $image1 = handleImageUpload($_FILES['image1']);
    $image2 = handleImageUpload($_FILES['image2']);
    $image3 = handleImageUpload($_FILES['image3']);
    
    try {
        // Check if slug already exists
        $stmt = $conn->prepare("SELECT id FROM blogs WHERE slug = ?");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $slug = $slug . '-' . time(); // Append timestamp to make it unique
        }
        
        // Insert blog post
        $stmt = $conn->prepare("INSERT INTO blogs (title, slug, content, thumbnail, image1, image2, image3, meta_description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Convert null values to empty strings for binding
        $thumbnail = $thumbnail ?? '';
        $image1 = $image1 ?? '';
        $image2 = $image2 ?? '';
        $image3 = $image3 ?? '';
        
        $stmt->bind_param("sssbbbbss", $title, $slug, $content, $thumbnail, $image1, $image2, $image3, $meta_description, $status);
        
        if ($stmt->execute()) {
            $success = "Blog post created successfully!";
            // Clear form
            $_POST = array();
        } else {
            $error = "Error creating blog post: " . $conn->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/evvh05hutvmru9shqxf9led5e88pe9gqnsydnt1r401suklt/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            height: 500,
            menubar: true
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Add New Blog</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">Back to Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="meta_description" class="form-label">Meta Description</label>
                <textarea class="form-control" id="meta_description" name="meta_description" rows="2"><?php echo isset($_POST['meta_description']) ? htmlspecialchars($_POST['meta_description']) : ''; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="thumbnail" class="form-label">Thumbnail Image</label>
                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                <small class="text-muted">Recommended size: 800x600 pixels</small>
            </div>

            <div class="mb-3">
                <label for="image1" class="form-label">Additional Image 1</label>
                <input type="file" class="form-control" id="image1" name="image1" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="image2" class="form-label">Additional Image 2</label>
                <input type="file" class="form-control" id="image2" name="image2" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="image3" class="form-label">Additional Image 3</label>
                <input type="file" class="form-control" id="image3" name="image3" accept="image/*">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="draft" <?php echo (isset($_POST['status']) && $_POST['status'] === 'draft') ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo (isset($_POST['status']) && $_POST['status'] === 'published') ? 'selected' : ''; ?>>Published</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create Blog Post</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 