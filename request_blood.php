<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

// Initialize blood requests array if not set
if (!isset($_SESSION['blood_requests'])) {
    $_SESSION['blood_requests'] = [];
}

// Handle blood request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_request'])) {
    $bloodType = $_POST['blood_type'];
    $quantity = $_POST['quantity'];
    $details = $_POST['details'];

    // Save the blood request
    $_SESSION['blood_requests'][] = [
        'user' => $_SESSION['role'],  // User's role (for identification)
        'blood_type' => $bloodType,
        'quantity' => $quantity,
        'details' => $details,
        'status' => 'Pending',  // Default status
    ];

    // Redirect to user dashboard after submission
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Blood</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

<h2 class="text-center mb-4">Create a Blood Request</h2>

<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <form method="POST" class="border p-4 rounded shadow" style="width: 100%; max-width: 400px;">
        <div class="mb-3">
            <label class="form-label">Blood Type</label>
            <select name="blood_type" class="form-select" required>
                <option value="">Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required placeholder="Enter quantity (in liters)">
        </div>
        <div class="mb-3">
            <label class="form-label">Additional Details</label>
            <textarea name="details" class="form-control" placeholder="Any additional details" rows="3"></textarea>
        </div>
        <div class="d-grid">
            <button type="submit" name="submit_request" class="btn btn-success">Submit Request</button>
        </div>
    </form>
</div>

</body>
</html>
