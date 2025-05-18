<?php
session_start();
require_once '../connectDB/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing ID']);
    exit;
}

$id = (int)$data['id'];

if (!$conn) {
    http_response_code(500);
    echo json_encode(['error' => 'DB connection failed']);
    exit;
}

$sql = "DELETE FROM blood_banks WHERE id = $id";
if (mysqli_query($conn, $sql)) {
    if (mysqli_affected_rows($conn) > 0) {
        echo json_encode(['message' => 'Record deleted successfully']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Delete failed', 'mysql_error' => mysqli_error($conn)]);
}

mysqli_close($conn);
?>
