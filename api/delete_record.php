<?php
session_start();
require_once '../connectDB/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true); //reads raw POST data from the HTTP request body. //JSON string into a PHP associative array 
$id = trim($data['id'] ?? '');

if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing ID']);
    exit;
}

try {
    // $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete statement.
    $stmt = $pdo->prepare("DELETE FROM blood_banks WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Record deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
