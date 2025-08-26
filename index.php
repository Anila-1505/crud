<?php require_once __DIR__ . '/header.php'; ?>

<?php
// Fetch posts
$stmt = $pdo->query("SELECT p.id, p.title, p.content, p.created_at, u.username
                     FROM posts p
                     LEFT JOIN users u ON u.id = p.user_id
                     ORDER BY p.created_at DESC");
$posts = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3">All Posts</h1>
  <?php if (is_logged_in()): ?>
    <a href="/create.php" class="btn btn-primary">+ New Post</a>
  <?php endif; ?>
</div>

<?php if (empty($posts)): ?>
  <div class="alert alert-info">No posts yet.</div>
<?php else: ?>
  <div class="list-group">
    <?php foreach ($posts as $post): ?>
      <a href="/view.php?id=<?= $post['id'] ?>" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1"><?= htmlspecialchars($post['title']) ?></h5>
          <small><?= htmlspecialchars($post['created_at']) ?></small>
        </div>
        <p class="mb-1 text-truncate"><?= htmlspecialchars($post['content']) ?></p>
        <small>By <?= htmlspecialchars($post['username'] ?? 'Unknown') ?></small>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/footer.php'; ?>
