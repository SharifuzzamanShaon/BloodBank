<?php
session_start();

// Handle logout request
if (isset($_POST['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login page
    exit;
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Initialize dummy blood bank data
if (!isset($_SESSION['blood_banks'])) {
    $_SESSION['blood_banks'] = [
        ['name' => 'City Blood Bank', 'location' => 'Dhaka', 'contact' => '0123456789', 'blood_type' => 'A+', 'availability' => 'available'],
        ['name' => 'Red Cross', 'location' => 'Chittagong', 'contact' => '0987654321', 'blood_type' => 'B-', 'availability' => 'not_available'],
        ['name' => 'Safe Blood Center', 'location' => 'Khulna', 'contact' => '01712345678', 'blood_type' => 'O+', 'availability' => 'available'],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Blood Bank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Blood Bank Inventory</h2>
        <form method="POST">
            <button type="submit" name="logout" class="btn btn-outline-danger">Logout</button>
        </form>
    </div>
    
    <a href="add.php" class="btn btn-primary mb-3">Add New Entry</a>

    <!-- Blood Bank Table -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Contact</th>
            <th>Blood Type</th>
            <th>Availability</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($_SESSION['blood_banks'] as $index => $bank): ?>
            <tr>
                <td><?= htmlspecialchars($bank['name']) ?></td>
                <td><?= htmlspecialchars($bank['location']) ?></td>
                <td><?= htmlspecialchars($bank['contact']) ?></td>
                <td><?= htmlspecialchars($bank['blood_type']) ?></td>
                <td>
                    <span class="badge <?= $bank['availability'] === 'available' ? 'bg-success' : 'bg-danger' ?>">
                        <?= ucfirst($bank['availability']) ?>
                    </span>
                </td>
                <td>
                    <a href="edit.php?index=<?= $index ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?index=<?= $index ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
