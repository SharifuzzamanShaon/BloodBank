<?php
session_start();
require_once '../connectDB/db.php'; 


header('Content-Type: application/json');

try {
    // Base query
    $sql = "
        SELECT br.*, u.username 
        FROM blood_request br 
        JOIN users u ON br.user_id = u.id 
        WHERE 1=1
    ";
    $params = [];

    // Optional filter: status
    if (!empty($_GET['status'])) {
        $sql .= " AND br.status = :status";
        $params[':status'] = $_GET['status'];
    }

    // Optional filter: bloodgroup
    if (!empty($_GET['bloodgroup'])) {
        $sql .= " AND br.bloodgroup = :bloodgroup";
        $params[':bloodgroup'] = $_GET['bloodgroup'];
    }

    // Optional filter: location (partial match)
    if (!empty($_GET['location'])) {
        $sql .= " AND br.location LIKE :location";
        $params[':location'] = '%' . $_GET['location'] . '%';
    }

    // Final order
    $sql .= " ORDER BY br.requested_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($requests);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
