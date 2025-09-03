<?php
require_once __DIR__ . '/config.php';

if (!is_logged_in()) {
    die("You must be logged in to edit a post.");
}

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$post_id = (int) $_GET['id'];
$current_user_id = $_SESSION['user_id']; // logged-in user

// fetch post only if it belongs to the logged-in user
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$post_id, $current_user_id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found or you are not allowed to edit this post.");
}

// update post when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$title, $content, $post_id, $current_user_id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Edit Post</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" class="form-control" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>