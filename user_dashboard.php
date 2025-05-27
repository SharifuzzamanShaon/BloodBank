<?php
session_start();
require_once './connectDB/db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}

if (isset($_POST['logout'])) {
  session_unset();
  session_destroy();
  header("Location: login.html");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>User Dashboard - Blood Bank</title>
  <link rel="icon" href="./image/image.png" type="image/png" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="./styles/userDashboard.css" />
</head>

<body class="bg-light">
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-custom mb-4">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">Blood Bank Dashboard</a>

      <div class="d-flex">
        <!-- Request for Blood Button -->
        <a href="#" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#requestBloodModal">
          Request for Blood <i class="bi bi-droplet-fill"></i>
        </a>
        <form method="POST" class="d-inline">
          <button type="submit" name="logout" class="btn btn-danger">
            Logout <i class="bi bi-box-arrow-right"></i>
          </button>
        </form>
      </div>
    </div>
  </nav>
  <div class="container">
    <!-- Search Form -->
    <form id="searchForm" class="row g-3 mb-4">
      <div class="col-md-2 justify-content-center">
        <input type="text" name="location" class="form-control" placeholder="Search by location" />
      </div>
      <div class="col-md-2">
        <select name="blood_type" class="form-select">
          <option value="">All Blood Types</option>
          <?php
          $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
          foreach ($bloodTypes as $bt) {
            echo "<option value='$bt'>$bt</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-danger w-100"><i class="bi bi-search mr-2 "></i> Search</button>
      </div>
    </form>
    <!-- Results Cards / Display available/unavailable both blood donations -->
    <div id="resultsBody" class="row g-4 mb-4">
      <div class="col-12 text-center">
        <div class="text-muted">Loading...</div>
      </div>
    </div>
  </div>
  <!-- Popup modal -->
  <div class="modal fade" id="requestBloodModal" tabindex="-1" aria-labelledby="requestBloodLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="requestBloodLabel">Request for Blood</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" onsubmit="return handleBloodRequest(event)">
          <div class="modal-body">
            <div class="mb-3">
              <label for="bloodGroup" class="form-label">Blood Group</label>
              <select name="blood_group" id="bloodGroup" class="form-select" required>
                <option value="">Select Blood Group</option>
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
              <label for="quantity" class="form-label">Blood Quantity (in bags):</label>
              <input type="number" name="quantity" id="quantity" class="form-control" min="1" required />
            </div>
            <div class="mb-3">
              <label for="location" class="form-label">Location</label>
              <input type="text" name="location" id="location" class="form-control" required />
            </div>
            <div class="mb-3">
              <label for="contact" class="form-label">Contact Info</label>
              <input type="text" name="contact" id="contact" class="form-control" required />
            </div>
            <div>
              <label for="purpose" class="form-label">Reason for Request</label>
              <select name="purpose" id="purpose" class="form-select">
                <option value="">Select Reason</option>
                <option value="Emergency">Emergency</option>
                <option value="Surgery">Surgery</option>
                <option value="Donation">Donation</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="details" class="form-label">Additional Details</label>
              <textarea name="details" id="details" class="form-control" rows="3"></textarea>
            </div>
          </div>
          <div id="error-msg" class="alert alert-danger d-none"></div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Submit Request</button>
          </div>
        </form>
      </div>

    </div>
  </div>
  <!-- Footer -->
  <footer class="bg-danger text-white text-center py-3 mt-auto mt-4">
    <p class="mb-0">© 2023 Blood Bank. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./js-config/userDashboard.js"></script>
</body>

</html>