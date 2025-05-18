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

  <style>
    /* Colorful header background gradient */
    .navbar-gradient {
      background:  #d4145a;
    }

    /* Navbar brand styling */
    .navbar-brand {
      font-weight: 700;
      font-size: 1.8rem;
      color: #fff !important;
      text-shadow: 1px 1px 2px #00000088;
    }

    /* Navbar links styling */
    .nav-link {
      color: #fff !important;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .nav-link:hover,
    .nav-link:focus {
      color: #000 !important;
      background-color: #fff;
      border-radius: 0.375rem;
    }

    /* Responsive table container */
    .table-responsive {
      box-shadow: 0 0 15px rgb(0 0 0 / 0.1);
      border-radius: 0.5rem;
      overflow: hidden;
    }

    /* Badge with slightly bigger font for availability */
    .badge {
      font-size: 0.9rem;
      font-weight: 600;
    }

    /* Buttons with custom colors */
    .btn-primary-custom {
      background-color: #d4145a;
      border: none;
    }

    .btn-primary-custom:hover {
      background-color: #a31043;
    }
    .title{
        font-size: 1.4rem;
        font-weight: 600;
        color: #d4145a;
    }
  </style>
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
      <input type="text" id="searchInput" class="form-control" placeholder="Search by Name or Location" onkeyup="filterTable()" />
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
  <script>
    async function fetchBloodBanks() {
      try {
        const response = await fetch('./api/blood_bank.php');
        if (!response.ok) throw new Error("Network response was not ok");
        const data = await response.json();

        const tbody = document.getElementById('bloodBankTable');
        tbody.innerHTML = '';

        if (!data.length) {
          tbody.innerHTML =
            '<tr><td colspan="6" class="text-center">No records found</td></tr>';
          return;
        }

        data.forEach(bank => {
          const row = document.createElement('tr');
          row.innerHTML = `
              <td>${escapeHtml(bank.name)}</td>
              <td>${escapeHtml(bank.location)}</td>
              <td>${escapeHtml(bank.contact)}</td>
              <td>${escapeHtml(bank.blood_type)}</td>
              <td>
                  <span class="badge ${bank.availability === 'available' ? 'bg-success' : 'bg-danger'}">
                      ${capitalize(bank.availability)}
                  </span>
              </td>
              <td>
                <a href="#" class="btn btn-warning btn-sm me-1" onclick='openEditModal(${JSON.stringify(bank)})'>
                    <i class="bi bi-pencil-square"></i>
                </a>
                  <a href="./admin-controller/delete.php?id=${encodeURIComponent(bank.id)}" class="btn btn-danger btn-sm"
                    onclick="handleDelete(${bank.id})">
                        <i class="bi bi-trash"></i>
                    </a>
              </td>
          `;
          tbody.appendChild(row);
        });
      } catch (error) {
        console.error("Error fetching data:", error);
        document.getElementById('bloodBankTable').innerHTML =
          '<tr><td colspan="6" class="text-center text-danger">Failed to load data</td></tr>';
      }
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function capitalize(text) {
      if (!text) return '';
      return text.charAt(0).toUpperCase() + text.slice(1);
    }

    fetchBloodBanks();

    // Event listener for the edit button
  function openEditModal(bank) {
    document.getElementById("edit-id").value = bank.id;
    document.getElementById("edit-name").value = bank.name;
    document.getElementById("edit-location").value = bank.location;
    document.getElementById("edit-contact").value = bank.contact;
    document.getElementById("edit-blood-type").value = bank.blood_type;
    document.getElementById("edit-availability").value = bank.availability;

    const editModal = new bootstrap.Modal(document.getElementById("editModal"));
    editModal.show();
  }
    // Filter table function
    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("bloodBankTable");
        const rows = table.getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName("td");
            let found = false;

            for (let j = 0; j < cells.length - 1; j++) { // Exclude the last cell (Actions)
                if (cells[j]) {
                    const txtValue = cells[j].textContent || cells[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }

            rows[i].style.display = found ? "" : "none";
        }
    }
//   edit form handler
    async function handleEdit(event) {
        event.preventDefault();
        const id = document.getElementById("edit-id").value;
        const name = document.getElementById("edit-name").value;
        const location = document.getElementById("edit-location").value;
        const contact = document.getElementById("edit-contact").value;
        const blood_type = document.getElementById("edit-blood-type").value;
        const availability = document.getElementById("edit-availability").value;
        const data = { id, name, location, contact, blood_type, availability }; 
        try {
            const response = await fetch('./api/edit_record.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
    
            if (response.ok) {
                alert("Record updated successfully!");
                fetchBloodBanks(); // Refresh the table
                const editModal = bootstrap.Modal.getInstance(document.getElementById("editModal"));
                editModal.hide();
            } else {
                const errorData = await response.json();
                alert("Error: " + errorData.error);
            }
        } catch (error) {
            console.error('Error:', error);
            alert("An error occurred while updating the record.");
        }
    }
    const handleDelete = async()=>{
        try {
            const response = await fetch('./api/delete_record.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
    
            if (response.ok) {
                alert("Record deleted successfully!");
                fetchBloodBanks(); // Refresh the table
            } else {
                const errorData = await response.json();
                alert("Error: " + errorData.error);
            }
        } catch (error) {
            console.error('Error:', error);
            alert("An error occurred while deleting the record.");
        }
    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
