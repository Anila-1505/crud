<?php require_once __DIR__ . '/header.php'; ?>

<?php
$username = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === "" || $password === "") {
        $errors[] = "Username and password are required.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Invalid credentials.";
        }
    }
}
?>

<h1 class="h3 mb-3">Login</h1>

<?php if ($errors): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="POST">
  <div class="mb-3">
    <label class="form-label">Username</label>
    <input class="form-control" name="username" value="<?= htmlspecialchars($username) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input class="form-control" type="password" name="password">
  </div>
  <button class="btn btn-primary">Login</button>
  <a class="btn btn-link" href="/register.php">Create an account</a>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
