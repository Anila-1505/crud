<?php
$host = "localhost";
$db   = "crud_app";
$user = "root";
$pass = "";  // currently empty
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    session_start();
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
