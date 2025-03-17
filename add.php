<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $blood_type = $_POST['blood_type'] ?? '';
    $availability = $_POST['availability'] ?? '';

    if ($name && $location && $contact && $blood_type && $availability) {
        $_SESSION['blood_banks'][] = [
            'name' => $name,
            'location' => $location,
            'contact' => $contact,
            'blood_type' => $blood_type,
            'availability' => $availability
        ];
    }

    header("Location: index.php");  // Redirect back to list
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Blood Bank Info</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <h2>Add Blood Bank Information</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Blood Type</label>
            <select name="blood_type" class="form-control" required>
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
            <label class="form-label">Availability</label>
            <select name="availability" class="form-control" required>
                <option value="available">Available</option>
                <option value="not_available">Not Available</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add Entry</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>

</body>
</html>
