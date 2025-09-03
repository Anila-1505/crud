<?php
require_once __DIR__ . '/config.php';

if (!is_logged_in()) {
    die("You must be logged in to delete a post.");
}

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$post_id = (int) $_GET['id'];
$current_user_id = $_SESSION['user_id']; // logged-in user

// delete only if it belongs to the logged-in user
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$post_id, $current_user_id]);

if ($stmt->rowCount() === 0) {
    die("Post not found or you are not allowed to delete this post.");
}

header("Location: index.php");
exit;