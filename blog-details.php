<?php
require_once 'backend/conn.php';

$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    header('Location: blogs.php');
    exit();
}

// Fetch the specific blog
$stmt = $conn->prepare("SELECT b.*, a.username as author_name 
                       FROM blogs b 
                       JOIN admins a ON b.author_id = a.id 
                       WHERE b.slug = ? AND b.status = 'published'");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: blogs.php');
    exit();
}

$blog = $result->fetch_assoc();

// Include header
include 'templates/header.php';
?>
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <article class="bg-white shadow-lg rounded-lg overflow-hidden">
        <?php if ($blog['thumbnail']): ?>
        <img src="<?php echo htmlspecialchars($blog['thumbnail']); ?>" 
             alt="<?php echo htmlspecialchars($blog['title']); ?>"
             class="w-full h-96 object-cover">
        <?php endif; ?>
        
        <div class="p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                <?php echo htmlspecialchars($blog['title']); ?>
            </h1>
            
            <div class="flex items-center space-x-4 mb-6">
                <span class="text-gray-600">
                    By <?php echo htmlspecialchars($blog['author_name']); ?>
                </span>
                <span class="text-gray-400">•</span>
                <span class="text-gray-600">
                    <?php echo date('F j, Y', strtotime($blog['created_at'])); ?>
                </span>
            </div>
            
            <div class="prose max-w-none">
                <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
            </div>
            
            <?php if ($blog['image1'] || $blog['image2']): ?>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php if ($blog['image1']): ?>
                <img src="<?php echo htmlspecialchars($blog['image1']); ?>" 
                     alt="Additional image 1"
                     class="w-full h-64 object-cover rounded-lg">
                <?php endif; ?>
                
                <?php if ($blog['image2']): ?>
                <img src="<?php echo htmlspecialchars($blog['image2']); ?>" 
                     alt="Additional image 2"
                     class="w-full h-64 object-cover rounded-lg">
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </article>
    
    <div class="mt-8">
        <a href="blogs.php" 
           class="text-indigo-600 hover:text-indigo-900">
            ← Back to all blogs
        </a>
    </div>
</div>
<?php
// Include footer
include 'templates/footer.php';
?> 