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
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
  />
  <style>
    .navbar-custom {
      background: linear-gradient(to right, #dc3545, #ff6f61);
    }

    .navbar-brand, .nav-link, .btn-outline-light {
      color: #fff !important;
    }

    .table thead th {
      background-color: #f8f9fa;
    }

    @media (max-width: 576px) {
      .table-responsive {
        font-size: 0.875rem;
      }
    }
  </style>
</head>
<body class="bg-light">
  <!-- Header -->
  <nav class="navbar navbar-expand-lg navbar-custom mb-4">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">Blood Bank Dashboard</a>
      <div class="d-flex">
        <form method="POST" class="d-inline">
          <button type="submit" name="logout" class="btn btn-outline-light text-black">
            Logout
          </button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container">
    <!-- Search Form -->
    <form id="searchForm" class="row g-3 mb-4">
      <div class="col-md-2 justify-content-center">
        <input
          type="text"
          name="location"
          class="form-control"
          placeholder="Search by location"
        />
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
      <div class="col-md-4">
        <button type="submit" class="btn btn-danger w-100">Search</button>
      </div>
    </form>

    <!-- Results Table -->
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle text-center">
        <thead class="table-danger">
          <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Contact</th>
            <th>Blood Type</th>
            <th>Availability</th>
          </tr>
        </thead>
        <tbody id="resultsBody">
          <tr>
            <td colspan="5" class="text-center">Loading...</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    async function fetchResults() {
      const form = document.getElementById("searchForm");
      const formData = new FormData(form);
      const params = new URLSearchParams(formData);
      const tbody = document.getElementById("resultsBody");
      tbody.innerHTML = `<tr><td colspan="5" class="text-center">Loading...</td></tr>`;

      try {
        const response = await fetch("./api/blood_bank.php?" + params.toString());

        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        tbody.innerHTML = "";

        if (!data || data.length === 0) {
          tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">No results found</td></tr>`;
          return;
        }

        data.forEach((bank) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${bank.name}</td>
            <td>${bank.location}</td>
            <td>${bank.contact}</td>
            <td><span class="fw-bold">${bank.blood_type}</span></td>
            <td>
              <span class="badge ${bank.availability === 'available' ? 'bg-success' : 'bg-danger'}">
                ${bank.availability.charAt(0).toUpperCase() + bank.availability.slice(1)}
              </span>
            </td>
          `;
          tbody.appendChild(row);
        });
      } catch (error) {
        console.error("Error fetching blood bank data:", error);
        tbody.innerHTML = `<tr><td colspan="5" class="text-danger text-center">Error loading data</td></tr>`;
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      fetchResults();

      document.getElementById("searchForm").addEventListener("submit", function (e) {
        e.preventDefault();
        fetchResults();
      });
    });
  </script>
</body>
</html>
