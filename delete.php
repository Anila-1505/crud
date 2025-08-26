<?php
require_once __DIR__ . '/config.php';
if (!is_logged_in()) { header('Location: /login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

$id = $_POST['id'] ?? null;
if (!$id || !ctype_digit($id)) {
    http_response_code(400);
    echo "Invalid post ID.";
    exit;
}

$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$id]);

header("Location: /");
exit;
?>
