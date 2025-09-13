<?php
require_once __DIR__ . '/config.php';

// ✅ Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$search = $_GET['search'] ?? '';
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$limit  = 5; // posts per page
$offset = ($page - 1) * $limit;

// Count total posts for this user
$countSql = "SELECT COUNT(*) 
             FROM posts 
             WHERE user_id = :user_id";
$params = [':user_id' => $_SESSION['user_id']];

if (!empty($search)) {
    $countSql .= " AND (title LIKE :search OR content LIKE :search)";
    $params[':search'] = "%$search%";
}

$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$totalPosts = $stmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

// Fetch posts
$sql = "SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.user_id = :user_id";

if (!empty($search)) {
    $sql .= " AND (posts.title LIKE :search OR posts.content LIKE :search)";
}

$sql .= " ORDER BY posts.created_at DESC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
if (!empty($search)) $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
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

  <!-- Search Form -->
  <form method="get" class="d-flex mb-3">
    <input type="text" name="search" class="form-control me-2" placeholder="Search your posts..."
           value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn btn-primary">Search</button>
  </form>

  <?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
      <div class="card mb-3 shadow-sm rounded">
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
    <div class="alert alert-info">
      No posts <?= $search ? 'matching "<b>'.htmlspecialchars($search).'</b>"' : 'yet' ?>.
    </div>
  <?php endif; ?>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
    <nav>
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">Previous</a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Next</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
