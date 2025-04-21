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

<div class="pt-20"> <!-- Added padding to prevent nav overlap -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="bg-white shadow-lg rounded-lg overflow-hidden">
            <?php if ($blog['thumbnail']): ?>
            <div class="relative h-96">
                <img src="<?php echo htmlspecialchars($blog['thumbnail']); ?>" 
                     alt="<?php echo htmlspecialchars($blog['title']); ?>"
                     class="absolute inset-0 w-full h-full object-cover">
            </div>
            <?php endif; ?>
            
            <div class="p-8">
                <div class="flex items-center text-sm text-gray-500 mb-4">
                    <span class="font-medium"><?php echo htmlspecialchars($blog['author_name']); ?></span>
                    <span class="mx-2">•</span>
                    <span><?php echo date('F j, Y', strtotime($blog['created_at'])); ?></span>
                </div>

                <h1 class="text-4xl font-bold text-gray-900 mb-6">
                    <?php echo htmlspecialchars($blog['title']); ?>
                </h1>
                
                <div class="prose max-w-none text-gray-700 text-lg leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
                </div>
                
                <?php if ($blog['image1'] || $blog['image2']): ?>
                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php if ($blog['image1']): ?>
                    <div class="relative h-64 rounded-lg overflow-hidden">
                        <img src="<?php echo htmlspecialchars($blog['image1']); ?>" 
                             alt="Additional image 1"
                             class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($blog['image2']): ?>
                    <div class="relative h-64 rounded-lg overflow-hidden">
                        <img src="<?php echo htmlspecialchars($blog['image2']); ?>" 
                             alt="Additional image 2"
                             class="absolute inset-0 w-full h-full object-cover">
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </article>
        
        <div class="mt-8 flex justify-between items-center">
            <a href="blogs.php" 
               class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to all blogs
            </a>
            
            <div class="flex space-x-4">
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                        class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    Back to top
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.prose {
    max-width: 100%;
    color: #374151;
    line-height: 1.75;
}

.prose p {
    margin-bottom: 1.5em;
}

.prose img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 2em 0;
}

.prose h2 {
    font-size: 1.5em;
    font-weight: 600;
    margin-top: 2em;
    margin-bottom: 1em;
}

.prose h3 {
    font-size: 1.25em;
    font-weight: 600;
    margin-top: 1.5em;
    margin-bottom: 0.75em;
}

.prose ul, .prose ol {
    margin-bottom: 1.5em;
    padding-left: 1.5em;
}

.prose li {
    margin-bottom: 0.5em;
}

.prose blockquote {
    border-left: 4px solid #E5E7EB;
    padding-left: 1em;
    margin: 1.5em 0;
    font-style: italic;
    color: #6B7280;
}

.prose a {
    color: #4F46E5;
    text-decoration: underline;
}

.prose a:hover {
    color: #4338CA;
}
</style>

<?php
// Include footer
include 'templates/footer.php';
?> 