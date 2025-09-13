<?php
require_once __DIR__ . '/config.php';
session_start(); // if not already started

$search = $_GET['search'] ?? '';
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$limit  = 5; // posts per page
$offset = ($page - 1) * $limit;

// Count total posts
$countSql = "SELECT COUNT(*) 
             FROM posts 
             JOIN users ON posts.user_id = users.id";
$params = [];

if (!empty($search)) {
    $countSql .= " WHERE posts.title LIKE :search OR posts.content LIKE :search";
    $params[':search'] = "%$search%";
}

$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$totalPosts = $stmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

// Fetch posts
$sql = "SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id";

if (!empty($search)) {
    $sql .= " WHERE posts.title LIKE :search OR posts.content LIKE :search";
}

$sql .= " ORDER BY posts.created_at DESC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
if (!empty($search)) {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
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
  <title>All Blog Posts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-4">
  <h2 class="mb-3">All Posts</h2>

  <!-- Search Form -->
  <form method="get" class="d-flex mb-3">
    <input type="text" name="search" class="form-control me-2" placeholder="Search posts..."
           value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn btn-primary">Search</button>
  </form>

  <?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
      <div class="card mb-3 shadow-sm rounded">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
          <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
          <small class="text-muted">By <?= htmlspecialchars($post['username']) ?> | <?= $post['created_at'] ?></small>
          <div class="mt-2">
            <a href="view.php?id=<?= $post['id'] ?>" class="btn btn-info btn-sm">View</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-info">
      No posts <?= $search ? 'matching "<b>'.htmlspecialchars($search).'</b>"' : '' ?>.
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
