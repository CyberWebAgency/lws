<?php
session_start();
require_once '../backend/conn.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle blog deletion
if (isset($_POST['delete_blog'])) {
    $blog_id = $_POST['blog_id'];
    $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
}

// Fetch all blogs
$blogs = $conn->query("SELECT * FROM blogs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Blog Management</h2>
            <a href="add_blog.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add New Blog
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($blog = $blogs->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($blog['title']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $blog['status'] === 'published' ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($blog['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></td>
                        <td>
                            <a href="edit_blog.php?id=<?php echo $blog['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this blog?');">
                                <input type="hidden" name="blog_id" value="<?php echo $blog['id']; ?>">
                                <button type="submit" name="delete_blog" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 