<?php
session_start();
require_once '../connectDB/db.php';
// Set header to return JSON
header('Content-Type: application/json');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit;
}

// Read raw input and decode JSON
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$id = trim($data['id'] ?? '');
$name = trim($data['name'] ?? '');
$location = trim($data['location'] ?? '');
$contact = trim($data['contact'] ?? '');
$blood_type = trim($data['blood_type'] ?? '');
$availability = trim($data['availability'] ?? '');

if (!$id || !$name || !$location || !$contact || !$blood_type || !$availability) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update record
    $stmt = $pdo->prepare("UPDATE blood_banks SET name = ?, location = ?, contact = ?, blood_type = ?, availability = ? WHERE id = ?");
    $stmt->execute([$name, $location, $contact, $blood_type, $availability, $id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
