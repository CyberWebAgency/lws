<?php
include '../includes/config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$error = '';
$success = '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('index.php');
}

$id = (int)$_GET['id'];

// Fetch blog data
$sql = "SELECT * FROM blogs WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    redirect('index.php');
}

$blog = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $slug = strtolower(str_replace(' ', '-', $title));
    $content = sanitize($_POST['content']);
    $status = sanitize($_POST['status']);

    // Handle file upload
    $featured_image = $blog['featured_image'];
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['featured_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = '../uploads/' . $new_filename;
            
            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $upload_path)) {
                // Delete old image if exists
                if ($featured_image && file_exists('../uploads/' . $featured_image)) {
                    unlink('../uploads/' . $featured_image);
                }
                $featured_image = $new_filename;
            }
        }
    }

    $sql = "UPDATE blogs SET 
            title = '$title',
            slug = '$slug',
            content = '$content',
            featured_image = '$featured_image',
            status = '$status'
            WHERE id = $id";

    if ($conn->query($sql)) {
        $success = 'Blog post updated successfully!';
        // Refresh blog data
        $sql = "SELECT * FROM blogs WHERE id = $id";
        $result = $conn->query($sql);
        $blog = $result->fetch_assoc();
    } else {
        $error = 'Error updating blog post: ' . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Edit Blog</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">Back to Dashboard</a>
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

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($blog['content']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="featured_image" class="form-label">Featured Image</label>
                <?php if ($blog['featured_image']): ?>
                    <div class="mb-2">
                        <img src="../uploads/<?php echo $blog['featured_image']; ?>" alt="Current featured image" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="featured_image" name="featured_image">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="draft" <?php echo $blog['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo $blog['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Blog Post</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 