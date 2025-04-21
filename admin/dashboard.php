<?php
session_start();
require_once '../backend/conn.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
// Fetch all blogs
$stmt = $conn->prepare("SELECT b.*, a.username as author_name 
                       FROM blogs b 
                       JOIN admins a ON b.author_id = a.id 
                       ORDER BY b.created_at DESC");
$stmt->execute();
$blogs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get success message if exists
$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LWS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold">LWS Admin</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="logout.php" class="text-gray-700 hover:text-gray-900">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <?php if ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($success_message); ?></span>
            </div>
        <?php endif; ?>

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Blog Posts</h2>
            <a href="create-blog.php" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                Create New Blog
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <?php foreach ($blogs as $blog): ?>
                <li>
                    <div class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-lg font-medium text-indigo-600 truncate">
                                    <?php echo htmlspecialchars($blog['title']); ?>
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    By <?php echo htmlspecialchars($blog['author_name']); ?> on 
                                    <?php echo date('M d, Y', strtotime($blog['created_at'])); ?>
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="edit-blog.php?id=<?php echo $blog['id']; ?>" 
                                   class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <a href="delete-blog.php?id=<?php echo $blog['id']; ?>" 
                                   class="text-red-600 hover:text-red-900"
                                   onclick="return confirm('Are you sure you want to delete this blog?')">Delete</a>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                <?php echo htmlspecialchars($blog['summary']); ?>
                            </p>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html> 