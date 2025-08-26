<?php require_once __DIR__ . '/header.php'; ?>

<?php
$username = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($username === "") $errors[] = "Username is required.";
    if ($password === "") $errors[] = "Password is required.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    if (!$errors) {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $errors[] = "Username already taken.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            header("Location: login.php");
            exit;
        }
    }
}
?>

<h1 class="h3 mb-3">Register</h1>

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
  <div class="mb-3">
    <label class="form-label">Confirm Password</label>
    <input class="form-control" type="password" name="confirm">
  </div>
  <button class="btn btn-primary">Create Account</button>
  <a class="btn btn-link" href="/login.php">Already have an account?</a>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
