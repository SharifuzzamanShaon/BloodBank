<?php
session_start();
require_once './connectDB/db.php';

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
  <meta charset="UTF-8" />
  <title>Admin Dashboard - Blood Bank</title>
  <link rel="icon" href="./image/image.png" type="image/png" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="./styles/adminDashboard.css" />
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-gradient mb-4 shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="#"> Blood Bank Admin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon" style="filter: invert(1) brightness(2);"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav align-items-center gap-2">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./admin-controller/insertRecord.php">Add Entry</a>
          </li>
          <li class="nav-item">
            <form method="POST" class="m-0">
              <button type="submit" name="logout" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container">
    <div class="container my-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <!-- Title -->
        <h3 class=" m-0 flex-grow-1 title">🩸 Blood Bank Inventory</h3>

        <!-- Search Input -->
        <div class="flex-grow-1" style="max-width: 300px;">
          <input type="text" id="searchInput" class="form-control" placeholder="Search by Name or Location"
            onkeyup="filterTable()" />
        </div>

        <!-- Add Button -->
        <a href="./admin-controller/insertRecord.php" class="btn btn- px-4 text-white btn-primary-custom">
          ➕ Add New Entry
        </a>
      </div>
    </div>

    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-dark sticky-top">
          <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Contact</th>
            <th>Blood Type</th>
            <th>Availability</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="bloodBankTable">
        </tbody>
      </table>

    </div>

    <!-- edit modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" id="editForm" onsubmit="handleEdit(event)">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="editModalLabel">Edit Blood Bank Entry</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" id="edit-id" />

            <div class="mb-3">
              <label for="edit-name" class="form-label">Name</label>
              <input type="text" class="form-control" id="edit-name" required>
            </div>

            <div class="mb-3">
              <label for="edit-location" class="form-label">Location</label>
              <input type="text" class="form-control" id="edit-location" required>
            </div>

            <div class="mb-3">
              <label for="edit-contact" class="form-label">Contact</label>
              <input type="text" class="form-control" id="edit-contact" required>
            </div>

            <div class="mb-3">
              <label for="edit-blood-type" class="form-label">Blood Type</label>
              <select id="edit-blood-type" class="form-select" required>
                <option value="" disabled selected>Select Blood Type</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="edit-availability" class="form-label">Availability</label>
              <select id="edit-availability" class="form-select" required>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
              </select>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Save Changes</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
    <!-- End of edit modal -->

  </main>
  <script src="./js-config/adminDashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>