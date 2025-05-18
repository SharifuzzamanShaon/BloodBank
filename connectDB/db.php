<?php
$host = '127.0.0.1';     // or your DB host
$db   = 'bloodbank';     // your DB name
$user = 'root';          // your DB username
$pass = '';              // your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
?>
