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

// Include header
include 'templates/header.php';
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Our Blog</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($blogs as $blog): ?>
        <a href="blog-details.php?slug=<?php echo htmlspecialchars($blog['slug']); ?>" 
           class="block bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <?php if ($blog['thumbnail']): ?>
            <div class="relative h-48">
                <img src="<?php echo htmlspecialchars($blog['thumbnail']); ?>" 
                     alt="<?php echo htmlspecialchars($blog['title']); ?>"
                     class="absolute inset-0 w-full h-full object-cover">
            </div>
            <?php endif; ?>
            
            <div class="p-6">
                <div class="flex items-center text-sm text-gray-500 mb-2">
                    <span><?php echo htmlspecialchars($blog['author_name']); ?></span>
                    <span class="mx-2">•</span>
                    <span><?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                </div>
                
                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                    <?php echo htmlspecialchars($blog['title']); ?>
                </h2>
                
                <p class="text-gray-600 line-clamp-3">
                    <?php echo htmlspecialchars($blog['summary']); ?>
                </p>
                
                <div class="mt-4 flex items-center text-indigo-600">
                    <span class="text-sm font-medium">Read more</span>
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php
// Include footer
include 'templates/footer.php';
?> 