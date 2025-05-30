<?php
session_start();

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="./styles/adminDashboard.css" />
</head>

<body>
  <div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-custom text-white" id="sidebar-wrapper">
      <div class="sidebar-heading py-4 fw-bold fs-5">🩸 Blood Bank</div>
      <div class="list-group list-group-flush">
        <a href="#" class="list-group-item list-group-item-action bg-custom text-white"><i class="bi bi-border-all"></i>
          Dashboard</a>
        <a href="./admin-controller/insertRecord.php"
          class="list-group-item list-group-item-action bg-custom text-white"><i class="bi bi-plus-square-fill"></i>
          Add Entry</a>
        <form method="POST" class="m-0">
          <button type="submit" name="logout"
            class="list-group-item list-group-item-action bg-custom text-white border-0 text-start"> <i
              class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
      </div>
    </div>

    <!-- Page Content -->
    <div id="page-content-wrapper" class="w-100">
      <nav class="navbar navbar-expand-lg navbar-light border-bottom shadow-sm"
        style="background-color: rgb(220 53 69);">
        <div class="container-fluid">
          <button class="btn text-white" id="menu-toggle">
            <i class="bi bi-list fs-3"></i>
          </button>
          <span class="navbar-brand text-white d-none d-sm-inline fs-5">Admin Dashboard</span>
          <form method="POST" class="m-0">
            <button type="submit" name="logout"
              class="list-group-item list-group-item-action bg-custom text-white border-0 text-start"> <i
                class="bi bi-box-arrow-right"></i> Logout</button>
          </form>
        </div>
      </nav>

      <main class="container-fluid mt-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-4 mb-3">
          <input type="text" id="searchInput" class="form-control" placeholder="Search by Name or Location"
            onkeyup="filterTable()" style="max-width: 300px;">
          <li class="nav-item">
            <a href="./admin-controller/insertRecord.php" class="btn btn- px-4 text-white btn-primary-custom">
              ➕ Add New Entry
            </a>
          </li>
        </div>
        <div class="table-container mx-auto" >
          <div class="table-responsive" style="max-height: 600px; overflow-x: auto; overflow-y: auto;">
            <table class="table table-striped table-hover align-middle mb-0">
              <h3 class="m-0 text-custom flex-grow-1 display-8 text-center my-4 sticky-top">Inventory</h3>
              <thead class="table-danger sticky-top">
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
                <!-- data from db-->
              </tbody>
            </table>
          </div>
        </div>
        <!-- edit modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="editForm" onsubmit="handleEdit(event)">
              <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="editModalLabel">Edit Blood Bank Entry</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                  aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <input type="hidden" id="edit-id" />

                <div class="mb-3">
                  <label for="edit-name" class="form-label fw-bold">Name</label>
                  <input type="text" class="form-control" id="edit-name" required>
                </div>

                <div class="mb-3">
                  <label for="edit-location" class="form-label fw-bold">Location</label>
                  <input type="text" class="form-control" id="edit-location" required>
                </div>

                <div class="mb-3">
                  <label for="edit-contact" class="form-label fw-bold">Contact</label>
                  <input type="text" class="form-control" id="edit-contact" required>
                </div>

                <div class="mb-3">
                  <label for="edit-blood-type" class="form-label fw-bold">Blood Type</label>
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
                  <label for="edit-availability" class="form-label fw-bold">Availability</label>
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
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="./js-config/adminDashboard.js"></script>

</body>

</html>