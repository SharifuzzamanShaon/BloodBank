<?php
session_start();
require_once '../connectDB/db.php';

// Allow both admin and user roles
// if (!isset($_SESSION['logged_in']) || !in_array($_SESSION['role'], ['admin', 'user'])) {
//     http_response_code(403);
//     echo json_encode(['error' => 'Unauthorized']);
//     exit;
// }

header('Content-Type: application/json');

try {
    // Base query
    $sql = "SELECT * FROM blood_banks WHERE 1=1";
    $params = [];

    // Filter by blood type if provided
    if (!empty($_GET['blood_type'])) {
        $sql .= " AND blood_type = :blood_type";
        $params[':blood_type'] = $_GET['blood_type'];
    }

    // Filter by location if provided
    if (!empty($_GET['location'])) {
        $sql .= " AND location LIKE :location";
        $params[':location'] = '%' . $_GET['location'] . '%';
    }
    $sql .= " ORDER BY id DESC"; 
    // Prepare and execute
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Fetch and return data
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
