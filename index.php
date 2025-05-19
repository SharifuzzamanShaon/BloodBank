<?php
session_start();
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$role = $_SESSION['role'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Blood Bank - Home</title>
  <link rel="icon" href="../image/image.png" type="image/png" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="./styles/style.css"/>
</head>

<body class="d-flex flex-column min-vh-100">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="../image/image.png" alt="Blood Bank" width="30" height="30" class="d-inline-block align-text-top" />
        Blood Bank
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>"
              href="index.php">Home</a>
          </li>

          <?php if (!$isLoggedIn): ?>
            <li class="nav-item">
              <a class="nav-link" href="authModule/login.html">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="authModule/register.html">Register</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link"
                href="<?= $role === 'admin' ? 'admin_dashboard.php' : 'user_dashboard.php' ?>">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-danger" href="logout.php">Logout</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>

    </div>
  </nav>

  <!-- Main content area (fills height between navbar and footer) -->
  <main class="flex-grow-1">
    <!-- Hero Section -->
    <section class="bg-light text-center py-5 hero-section">
      <div class="container">
        <h1 class="display-6 fw-bold text-danger ">Donate Blood, Save Lives</h1>
        <p class="lead">Your small effort can give others a second chance at life.</p>
        <img src="./image/image.png" alt="Donate Blood" class="img-fluid" style="max-width: 100px;" />
      </div>
    </section>

    <!-- Search Blood Section -->
    <section class="container my-3">
      <!-- <h3 class="text-center mb-2 fs-5 ">Search for Blood</h3> -->
      <form class="row g-3 justify-content-center">
        <div class="col-md-2">
          <select class="form-select" required>
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
        <div class="col-md-2">
          <input type="text" class="form-control" placeholder="Enter City or Location" required />
        </div>
        <div class="col-md-2 d-grid">
          <button type="submit" class="btn btn-danger">I NEED BLOOD</button>
        </div>
      </form>
      <!-- Carousel -->
      <div id="bloodRequestCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000"
      style="max-width: 400px; margin: auto;">
        <div class="carousel-inner" id="blood-request-body">
          <div class="carousel-item active">
            <div class="d-flex flex-column justify-content-center align-items-center" style="height: 220px;">
              <i class="bi bi-droplet-half loading-icon"></i>
              <p class="loading-text">Loading blood requests...</p>
            </div>
          </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#bloodRequestCarousel" data-bs-slide="prev"
          aria-label="Previous">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#bloodRequestCarousel" data-bs-slide="next"
          aria-label="Next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>

    </section>
  </main>
  <!-- Footer -->
  <footer class="bg-danger text-white text-center py-3 mt-auto">
    <p class="mb-0">&copy; 2025 Blood Bank. All rights reserved.</p>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript">
    async function fetchBloodRequests() {
      const container = document.getElementById("blood-request-body");

      container.innerHTML = `
    <div class="carousel-item active">
      <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
        <p class="text-muted">Loading...</p>
      </div>
    </div>
  `;

      try {
        const response = await fetch("../api/get_blood_requests.php", {
          method: "GET",
          credentials: "include",
        });

        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        container.innerHTML = "";

        if (!data || data.length === 0) {
          container.innerHTML = `
        <div class="carousel-item active">
          <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
            <p class="text-muted">No blood requests found.</p>
          </div>
        </div>
      `;
          return;
        }

        data.forEach((req, index) => {
          const isActive = index === 0 ? "active" : "";
          const slide = document.createElement("div");
          slide.className = `carousel-item ${isActive}`;
          slide.innerHTML = `
        <div class="card mx-auto my-4" style="max-width: 500px;">
          <div class="card-body text-center">
            <h5 class="card-title text-danger">${req.bloodgroup} Blood Request</h5>
            <p><strong>Username:</strong> ${req.username}</p>
            <p><strong>Quantity:</strong> ${req.quantity}</p>
            <p><strong>Contact:</strong> ${req.contact}</p>
            <p><strong>Location:</strong> ${req.location}</p>
            <p><strong>Status:</strong> 
              <span class="badge ${req.status.toLowerCase() === "approved"
              ? "bg-success"
              : req.status.toLowerCase() === "pending"
                ? "bg-warning text-dark"
                : "bg-secondary"
            }">${req.status}</span>
            </p>
          </div>
        </div>
      `;
          container.appendChild(slide);
        });
      } catch (error) {
        console.error("Error fetching blood requests:", error);
        container.innerHTML = `
      <div class="carousel-item active">
        <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
          <p class="text-danger">Error: ${error.message}</p>
        </div>
      </div>
    `;
      }
    }
    // Run on page load
    document.addEventListener("DOMContentLoaded", fetchBloodRequests);

  </script>
</body>

</html>