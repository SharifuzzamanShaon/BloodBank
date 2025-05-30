<?php
session_start();
require_once '../connectDB/db.php';

header('Content-Type: application/json');

// Only allow admin to insert
if (!isset($_SESSION['logged_in']) || !in_array($_SESSION['role'], ['admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true); //reads raw POST data from the HTTP request body. //JSON string into a PHP associative array 



// Validate inputs
$name = trim($data['name'] ?? '');
$location = trim($data['location'] ?? '');
$contact = trim($data['contact'] ?? '');
$blood_type = trim($data['blood_type'] ?? '');
$availability = $data['availability'] ?? 'available';


if (!$name || !$location || !$contact || !$blood_type || !$availability) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

try {
    $sql = "INSERT INTO blood_banks (name, location, contact, blood_type, availability)
            VALUES (:name, :location, :contact, :blood_type, :availability)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':location' => $location,
        ':contact' => $contact,
        ':blood_type' => $blood_type,
        ':availability' => $availability
    ]);

    echo json_encode(['success' => true, 'message' => 'Record inserted successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
