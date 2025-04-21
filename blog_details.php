<?php
include 'includes/config.php';

if (!isset($_GET['slug'])) {
    redirect('index.php');
}

$slug = sanitize($_GET['slug']);

// Fetch blog post
$sql = "SELECT * FROM blogs WHERE slug = '$slug' AND status = 'published'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    redirect('index.php');
}

$blog = $result->fetch_assoc();
?>
    <?php include 'includes/header.php'; ?>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <article class="blog-post">
                    <h1 class="mb-4"><?php echo htmlspecialchars($blog['title']); ?></h1>
                    
                    <?php if ($blog['featured_image']): ?>
                        <img src="uploads/<?php echo $blog['featured_image']; ?>" 
                             alt="<?php echo htmlspecialchars($blog['title']); ?>" 
                             class="img-fluid rounded mb-4">
                    <?php endif; ?>

                    <div class="blog-content">
                        <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
                    </div>

                    <div class="mt-4 text-muted">
                        <small>Posted on <?php echo date('F j, Y', strtotime($blog['created_at'])); ?></small>
                    </div>
                </article>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>