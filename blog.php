<?php
include 'includes/config.php';

// Fetch all published blog posts
$sql = "SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Latest Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <h1 class="mb-4">Latest Blog Posts</h1>
        
        <div class="row">
            <?php while ($blog = $result->fetch_assoc()): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <?php if ($blog['featured_image']): ?>
                        <img src="uploads/<?php echo $blog['featured_image']; ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($blog['title']); ?>"
                             style="height: 200px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h5>
                        <p class="card-text">
                            <?php 
                            $content = strip_tags($blog['content']);
                            echo strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
                            ?>
                        </p>
                        <a href="blog_details.php?slug=<?php echo $blog['slug']; ?>" class="btn btn-primary">Read More</a>
                    </div>
                    <div class="card-footer text-muted">
                        <small>Posted on <?php echo date('F j, Y', strtotime($blog['created_at'])); ?></small>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 