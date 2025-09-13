<?php
$host = "localhost";
$db   = "crud_app";     // or the actual DB name you created
$user = "root";         // or other user you created
$pass = "1234";         // <-- EXACT password you used in step 2

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    session_start();
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
