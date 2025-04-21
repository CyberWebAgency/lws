<?php
require_once 'backend/conn.php';

// Fetch all published blogs
$stmt = $conn->prepare("SELECT b.*, a.username as author_name 
                       FROM blogs b 
                       JOIN admins a ON b.author_id = a.id 
                       WHERE b.status = 'published'
                       ORDER BY b.created_at DESC");
$stmt->execute();
$blogs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - LWS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Include your existing header/navigation here -->
    
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Our Blog</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($blogs as $blog): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php if ($blog['thumbnail']): ?>
                <img src="<?php echo htmlspecialchars($blog['thumbnail']); ?>" 
                     alt="<?php echo htmlspecialchars($blog['title']); ?>"
                     class="w-full h-48 object-cover">
                <?php endif; ?>
                
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">
                        <a href="blog-details.php?slug=<?php echo htmlspecialchars($blog['slug']); ?>" 
                           class="hover:text-indigo-600">
                            <?php echo htmlspecialchars($blog['title']); ?>
                        </a>
                    </h2>
                    
                    <p class="text-gray-600 mb-4">
                        <?php echo htmlspecialchars($blog['summary']); ?>
                    </p>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            By <?php echo htmlspecialchars($blog['author_name']); ?>
                        </span>
                        <span class="text-sm text-gray-500">
                            <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Include your existing footer here -->
</body>
</html> 