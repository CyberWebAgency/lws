<?php
require_once 'backend/conn.php';

// Get pagination parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 6;
$offset = ($page - 1) * $per_page;

// Get total number of published blogs
$total_blogs = $conn->query("SELECT COUNT(*) as count FROM blogs WHERE status = 'published'")->fetch_assoc()['count'];
$total_pages = ceil($total_blogs / $per_page);

// Get blogs for current page
$stmt = $conn->prepare("SELECT id, title, slug, meta_description, created_at FROM blogs WHERE status = 'published' ORDER BY created_at DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $per_page, $offset);
$stmt->execute();
$blogs = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Our Latest Articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .blog-card {
            height: 100%;
            transition: transform 0.3s;
        }
        .blog-card:hover {
            transform: translateY(-5px);
        }
        .blog-image {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <h1 class="text-center mb-5">Latest Blog Posts</h1>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php while ($blog = $blogs->fetch_assoc()): ?>
            <div class="col">
                <div class="card blog-card h-100">
                    <?php
                    // Display thumbnail if exists
                    $stmt = $conn->prepare("SELECT thumbnail FROM blogs WHERE id = ?");
                    $stmt->bind_param("i", $blog['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $thumbnail = $result->fetch_assoc()['thumbnail'];
                    
                    if ($thumbnail): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($thumbnail); ?>" class="card-img-top blog-image" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($blog['meta_description']); ?></p>
                        <p class="text-muted small">Posted on <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></p>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="blog_details.php?slug=<?php echo $blog['slug']; ?>" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <nav aria-label="Blog pagination" class="mt-5">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                    </li>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 