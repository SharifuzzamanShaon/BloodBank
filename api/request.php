<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// rest of your code...

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require '../connectDB/db.php';

header("Content-Type: application/json");

// Check login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized. Please log in."]);
    exit;
}

// Decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (
    !$data ||
    !isset($data['bloodgroup'], $data['contact'], $data['location'], $data['quantity'])
) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields."]);
    exit;
}

// Extract variables safely
$user_id = $_SESSION['user_id'];
$bloodgroup = $data['bloodgroup'];
$contact = $data['contact'];
$location = $data['location'];
$quantity = (int)$data['quantity'];
$purpose = $data['purpose'] ?? 'Testing purpose';

$sql = "INSERT INTO blood_request (user_id, bloodgroup, quantity, contact, location, purpose) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("isisss", $user_id, $bloodgroup, $quantity, $contact, $location, $purpose);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
ob_end_flush();
?>
