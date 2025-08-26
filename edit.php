<?php require_once __DIR__ . '/header.php'; ?>
<?php if (!is_logged_in()) { header('Location: /login.php'); exit; } ?>

<?php
$id = $_GET['id'] ?? null;
if (!$id || !ctype_digit($id)) {
    http_response_code(400);
    echo '<div class="alert alert-danger">Invalid post ID.</div>';
    require_once __DIR__ . '/footer.php';
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();
if (!$post) {
    http_response_code(404);
    echo '<div class="alert alert-danger">Post not found.</div>';
    require_once __DIR__ . '/footer.php';
    exit;
}

$title = $post['title'];
$content = $post['content'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title === "") $errors[] = "Title is required.";
    if ($content === "") $errors[] = "Content is required.";

    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $id]);
        header("Location: /view.php?id=" . $id);
        exit;
    }
}
?>

<h1 class="h3 mb-3">Edit Post</h1>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="POST">
  <div class="mb-3">
    <label class="form-label">Title</label>
    <input class="form-control" name="title" value="<?= htmlspecialchars($title) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Content</label>
    <textarea class="form-control" name="content" rows="6"><?= htmlspecialchars($content) ?></textarea>
  </div>
  <button class="btn btn-primary">Save Changes</button>
  <a href="/view.php?id=<?= $id ?>" class="btn btn-link">Cancel</a>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
