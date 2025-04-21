<?php
require_once 'backend/conn.php';

if (!isset($_GET['slug'])) {
    header("Location: blogs.php");
    exit();
}

$slug = $_GET['slug'];
$stmt = $conn->prepare("SELECT * FROM blogs WHERE slug = ? AND status = 'published'");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: blogs.php");
    exit();
}

$blog = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?> - Blog Post</title>
    <meta name="description" content="<?php echo htmlspecialchars($blog['meta_description']); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .blog-content img {
            max-width: 100%;
            height: auto;
            margin: 1rem 0;
        }
        .blog-image {
            max-height: 500px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <article class="blog-post">
            <header class="mb-4">
                <h1 class="display-4 mb-3"><?php echo htmlspecialchars($blog['title']); ?></h1>
                <p class="text-muted">Posted on <?php echo date('F j, Y', strtotime($blog['created_at'])); ?></p>
            </header>

            <?php if ($blog['thumbnail']): ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($blog['thumbnail']); ?>" 
                     class="img-fluid rounded mb-4 blog-image" 
                     alt="<?php echo htmlspecialchars($blog['title']); ?>">
            <?php endif; ?>

            <div class="blog-content">
                <?php echo $blog['content']; ?>
            </div>

            <?php if ($blog['image1'] || $blog['image2'] || $blog['image3']): ?>
            <div class="row mt-4">
                <?php if ($blog['image1']): ?>
                <div class="col-md-4 mb-3">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($blog['image1']); ?>" 
                         class="img-fluid rounded" alt="Additional image 1">
                </div>
                <?php endif; ?>
                
                <?php if ($blog['image2']): ?>
                <div class="col-md-4 mb-3">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($blog['image2']); ?>" 
                         class="img-fluid rounded" alt="Additional image 2">
                </div>
                <?php endif; ?>
                
                <?php if ($blog['image3']): ?>
                <div class="col-md-4 mb-3">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($blog['image3']); ?>" 
                         class="img-fluid rounded" alt="Additional image 3">
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </article>

        <div class="mt-5">
            <a href="blogs.php" class="btn btn-outline-primary">Back to Blog</a>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 