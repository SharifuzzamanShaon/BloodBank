<?php
session_start();
require_once '../connectDB/db.php'; //  db connection

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = trim($input['username'] ?? '');
    $password = $input['password'] ?? '';

    if (!$username || !$password) {
        http_response_code(400);
        echo json_encode(['error' => 'Username and password required']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE BINARY username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            echo json_encode([
                'redirect' => $user['role'] === 'admin' ? '/admin_dashboard.php' : '/user_dashboard.php'
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Incorrect password']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Username not found']);
    }
    exit;
}
?>