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
  <title>Add Blood Bank Info</title>
  <link rel="icon" href="./image/image.png" type="image/png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
    <div class="container">
      <a class="navbar-brand" href="#">Blood Bank Admin</a>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header bg-danger text-white text-center">
            <h4>Add Blood Bank Entry</h4>
          </div>
          <div class="card-body">
            <form id="bloodForm" method="POST" onsubmit=" insertNewRecord(event)">
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
                <select name="blood_type" class="form-select" required>
                  <option value="" disabled selected>Select blood type</option>
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
                <select name="availability" class="form-select" required>
                  <option value="available">Available</option>
                  <option value="unavailable">Not Available</option>
                </select>
              </div>
              <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Add Entry</button>
                <button type="button" class="btn btn-secondary" onclick="history.back()">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<script>
  const insertNewRecord = async (event)=>{
    const form = document.getElementById('bloodForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    console.log(data);
    
    try {
        const response = await fetch('../api/insert_record.php', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            alert("Record added successfully!");
            window.location.href = "/admin_dashboard.php";
        } else {
            const errorData = await response.json();
            alert("Error: " + errorData.error);
        }
    } catch (error) {
        console.error('Error:', error);
        alert("An error occurred while adding the record.");
    }
  }
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
