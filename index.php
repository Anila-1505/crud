<?php
require_once __DIR__ . '/config.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

// Fetch only the logged-in user's posts
$stmt = $pdo->prepare("SELECT posts.*, users.username 
                       FROM posts 
                       JOIN users ON posts.user_id = users.id 
                       WHERE posts.user_id = ? 
                       ORDER BY posts.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$posts = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Blog Posts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>My Posts</h2>
    <a href="create.php" class="btn btn-primary">+ New Post</a>
  </div>

  <?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
          <small class="text-muted">
            By <?= htmlspecialchars($post['username']) ?> | <?= $post['created_at'] ?>
          </small>
          <div class="mt-2">
            <a href="view.php?id=<?= $post['id'] ?>" class="btn btn-info btn-sm">View</a>
            <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="delete.php?id=<?= $post['id'] ?>" class="btn btn-danger btn-sm"
               onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-info">No posts yet.</div>
  <?php endif; ?>
</div>
</body>
</html>