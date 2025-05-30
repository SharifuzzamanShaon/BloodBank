<?php
session_start();
require_once '../connectDB/db.php';

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.html");
    exit;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Blood Bank Info</title>
  <link rel="icon" href="./image/image.png" type="image/png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <h2 class="text-center mb-4">Update Information</h2>
    <form method="POST" class="border p-4 rounded shadow w-50 mx-auto">
    <div class="mb-3">
        <label class="form-label fw-bold">Name</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($bloodBank['name']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Location</label>
        <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($bloodBank['location']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Contact Number</label>
        <input type="text" name="contact" class="form-control" value="<?= htmlspecialchars($bloodBank['contact']) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Blood Type</label>
        <select name="blood_type" class="form-control" required>
            <option value="A+" <?= $bloodBank['blood_type'] == 'A+' ? 'selected' : '' ?>>A+</option>
            <option value="A-" <?= $bloodBank['blood_type'] == 'A-' ? 'selected' : '' ?>>A-</option>
            <option value="B+" <?= $bloodBank['blood_type'] == 'B+' ? 'selected' : '' ?>>B+</option>
            <option value="B-" <?= $bloodBank['blood_type'] == 'B-' ? 'selected' : '' ?>>B-</option>
            <option value="O+" <?= $bloodBank['blood_type'] == 'O+' ? 'selected' : '' ?>>O+</option>
            <option value="O-" <?= $bloodBank['blood_type'] == 'O-' ? 'selected' : '' ?>>O-</option>
            <option value="AB+" <?= $bloodBank['blood_type'] == 'AB+' ? 'selected' : '' ?>>AB+</option>
            <option value="AB-" <?= $bloodBank['blood_type'] == 'AB-' ? 'selected' : '' ?>>AB-</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Availability</label>
        <select name="availability" class="form-control" required>
            <option value="available" <?= $bloodBank['availability'] == 'available' ? 'selected' : '' ?>>Available</option>
            <option value="not_available" <?= $bloodBank['availability'] == 'not_available' ? 'selected' : '' ?>>Not Available</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Update Info</button>
    <a href="index.php" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>
