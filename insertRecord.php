<?php
session_start();

// Redirect non-admin users to login page
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

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
  <link rel="icon" href="./image/image.png" type="image/png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <h2 class="text-center mb-4">Insert new collection</h2>
    <form id="bloodForm" method="POST" onsubmit="return validateForm()" class="mx-auto" style="max-width: 400px;">
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
        <input type="text" name="contact" id="contact" class="form-control" required>
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

<script>
function validateForm() {
    const contact = document.getElementById('contact').value.trim();
    const pattern = /^\+?[0-9]{10,15}$/;
    if (!pattern.test(contact)) {
        alert("Please enter a valid contact number (10 to 15 digits, optional '+').");
        return false;
    }

    return true; 
}
</script>
