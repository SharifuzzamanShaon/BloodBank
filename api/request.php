<?php
session_start();
require_once '../connectDB/db.php';

header('Content-Type: application/json');

// Only allow authenticated users
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Please log in.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
$bloodgroup = trim($data['bloodgroup'] ?? '');
$contact = trim($data['contact'] ?? '');
$location = trim($data['location'] ?? '');
$quantity = (int)($data['quantity'] ?? 0);
$purpose = trim($data['purpose'] ?? 'Testing purpose');

if (!$bloodgroup || !$contact || !$location || !$quantity) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields.']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $sql = "INSERT INTO blood_request (user_id, bloodgroup, quantity, contact, location, purpose)
            VALUES (:user_id, :bloodgroup, :quantity, :contact, :location, :purpose)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':bloodgroup' => $bloodgroup,
        ':quantity' => $quantity,
        ':contact' => $contact,
        ':location' => $location,
        ':purpose' => $purpose
    ]);

    echo json_encode(['success' => true, 'message' => 'Blood request submitted successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
