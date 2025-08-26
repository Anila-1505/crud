<?php require_once __DIR__ . '/header.php'; ?>

<?php
$id = $_GET['id'] ?? null;
if (!$id || !ctype_digit($id)) {
    http_response_code(400);
    echo '<div class="alert alert-danger">Invalid post ID.</div>';
    require_once __DIR__ . '/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, u.username FROM posts p LEFT JOIN users u ON u.id = p.user_id WHERE p.id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();
if (!$post) {
    http_response_code(404);
    echo '<div class="alert alert-danger">Post not found.</div>';
    require_once __DIR__ . '/footer.php';
    exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0"><?= htmlspecialchars($post['title']) ?></h1>
  <div>
    <?php if (is_logged_in()): ?>
      <a href="/edit.php?id=<?= $post['id'] ?>" class="btn btn-outline-secondary btn-sm">Edit</a>
      <form class="d-inline" action="/delete.php" method="POST" onsubmit="return confirm('Delete this post?')">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
      </form>
    <?php endif; ?>
  </div>
</div>
<p class="text-muted mb-2"><small>By <?= htmlspecialchars($post['username'] ?? 'Unknown') ?> • <?= htmlspecialchars($post['created_at']) ?></small></p>
<hr>
<p style="white-space:pre-wrap"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

<?php require_once __DIR__ . '/footer.php'; ?>
