<?php
session_start();
require_once '../connectDB/db.php'; //db connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $username   = trim($input['username'] ?? '');
    $email      = trim($input['email'] ?? '');
    $password   = $input['password'] ?? '';
    $bloodgroup = trim($input['bloodgroup'] ?? '');
    $contact    = trim($input['contact'] ?? '');
    $location   = trim($input['location'] ?? '');
    $role       = trim($input['role'] ?? 'user'); // default role "user"

    // Basic validation
    if (!$username || !$email || !$password || !$bloodgroup || !$contact || !$location) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT 1 FROM users WHERE BINARY username = :username OR email = :email LIMIT 1");
    $stmt->execute([':username' => $username, ':email' => $email]);
    if ($stmt->fetch()) {
        http_response_code(409); // Conflict
        echo json_encode(['error' => 'Username or email already taken']);
        exit;
    }

    // Hash password securely
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $insertStmt = $pdo->prepare("
        INSERT INTO users (username, email, password, bloodgroup, contact, location, role)
        VALUES (:username, :email, :password, :bloodgroup, :contact, :location, :role)
    ");

    $success = $insertStmt->execute([
        ':username'   => $username,
        ':email'      => $email,
        ':password'   => $passwordHash,
        ':bloodgroup' => $bloodgroup,
        ':contact'    => $contact,
        ':location'   => $location,
        ':role'       => $role,
    ]);

    if ($success) {
        echo json_encode(['message' => 'User registered successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to register user']);
    }
    exit;
}
?>
