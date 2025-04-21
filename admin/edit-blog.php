<?php
session_start();
require_once '../backend/conn.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';
$blog = null;

// Get blog ID from URL
$blog_id = $_GET['id'] ?? 0;
if (!$blog_id) {
    header('Location: dashboard.php');
    exit();
}

// Fetch the blog post
$stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ? AND author_id = ?");
$stmt->bind_param("ii", $blog_id, $_SESSION['admin_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: dashboard.php');
    exit();
}

$blog = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $slug = strtolower(str_replace(' ', '-', $title));
    $summary = $_POST['summary'] ?? '';
    $content = $_POST['content'] ?? '';
    $status = $_POST['status'] ?? 'draft';

    // Handle file uploads
    $upload_dir = '../uploads/blogs/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate unique filenames
    $thumbnail = $blog['thumbnail'];
    $image1 = $blog['image1'];
    $image2 = $blog['image2'];

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $file_extension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('thumb_') . '_' . time() . '.' . $file_extension;
        $thumbnail = $upload_dir . $new_filename;
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail);
    }

    if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK) {
        $file_extension = pathinfo($_FILES['image1']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('img1_') . '_' . time() . '.' . $file_extension;
        $image1 = $upload_dir . $new_filename;
        move_uploaded_file($_FILES['image1']['tmp_name'], $image1);
    }

    if (isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK) {
        $file_extension = pathinfo($_FILES['image2']['name'], PATHINFO_EXTENSION);
        $new_filename = uniqid('img2_') . '_' . time() . '.' . $file_extension;
        $image2 = $upload_dir . $new_filename;
        move_uploaded_file($_FILES['image2']['tmp_name'], $image2);
    }

    try {
        $stmt = $conn->prepare("UPDATE blogs SET title = ?, slug = ?, summary = ?, content = ?, thumbnail = ?, image1 = ?, image2 = ?, status = ? WHERE id = ? AND author_id = ?");
        $stmt->bind_param("ssssssssii", $title, $slug, $summary, $content, $thumbnail, $image1, $image2, $status, $blog_id, $_SESSION['admin_id']);
        
        if ($stmt->execute()) {
            $success = 'Blog post updated successfully!';
            // Refresh blog data
            $stmt = $conn->prepare("SELECT * FROM blogs WHERE id = ?");
            $stmt->bind_param("i", $blog_id);
            $stmt->execute();
            $blog = $stmt->get_result()->fetch_assoc();
        } else {
            $error = 'Error updating blog post.';
        }
    } catch (Exception $e) {
        $error = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog - LWS Admin</title>
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
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                    <a href="logout.php" class="text-gray-700 hover:text-gray-900">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-2xl font-bold mb-6">Edit Blog Post</h2>

                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo htmlspecialchars($success); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" required
                               value="<?php echo htmlspecialchars($blog['title']); ?>"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="summary" class="block text-sm font-medium text-gray-700">Summary</label>
                        <textarea name="summary" id="summary" rows="3" required
                                  class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"><?php echo htmlspecialchars($blog['summary']); ?></textarea>
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea name="content" id="content" rows="10" required
                                  class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"><?php echo htmlspecialchars($blog['content']); ?></textarea>
                    </div>

                    <div>
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail Image</label>
                        <?php if ($blog['thumbnail']): ?>
                            <div class="mb-2">
                                <img src="<?php echo htmlspecialchars($blog['thumbnail']); ?>" 
                                     alt="Current thumbnail" 
                                     class="h-32 w-auto">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="thumbnail" id="thumbnail"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="image1" class="block text-sm font-medium text-gray-700">Additional Image 1</label>
                        <?php if ($blog['image1']): ?>
                            <div class="mb-2">
                                <img src="<?php echo htmlspecialchars($blog['image1']); ?>" 
                                     alt="Current image 1" 
                                     class="h-32 w-auto">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image1" id="image1"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="image2" class="block text-sm font-medium text-gray-700">Additional Image 2</label>
                        <?php if ($blog['image2']): ?>
                            <div class="mb-2">
                                <img src="<?php echo htmlspecialchars($blog['image2']); ?>" 
                                     alt="Current image 2" 
                                     class="h-32 w-auto">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image2" id="image2"
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="draft" <?php echo $blog['status'] === 'draft' ? 'selected' : ''; ?>>Draft</option>
                            <option value="published" <?php echo $blog['status'] === 'published' ? 'selected' : ''; ?>>Published</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="dashboard.php" 
                           class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">
                            Cancel
                        </a>
                        <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Blog Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 