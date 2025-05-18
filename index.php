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
            <a class="nav-link active" href="#">Home</a>
          </li>
          <?php if (!$isLoggedIn): ?>
            <li class="nav-item">
              <a class="nav-link" href="authModule/login.html">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.html">Register</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= $role === 'admin' ? 'admin_dashboard.php' : 'user_dashboard.php' ?>">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main content area (fills height between navbar and footer) -->
  <main class="flex-grow-1">
    <!-- Hero Section -->
    <section class="bg-light text-center py-5">
      <div class="container">
        <h1 class="display-5 fw-bold text-danger">Donate Blood, Save Lives</h1>
        <p class="lead">Your small effort can give others a second chance at life.</p>
        <img src="./image/image.png" alt="Donate Blood" class="img-fluid" style="max-width: 150px;" />
      </div>
    </section>

    <!-- Search Blood Section -->
    <section class="container my-5">
      <h2 class="text-center mb-4">Search for Blood</h2>
      <form class="row g-3 justify-content-center">
        <div class="col-md-4">
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
        <div class="col-md-4">
          <input type="text" class="form-control" placeholder="Enter City or Location" required />
        </div>
        <div class="col-md-2 d-grid">
          <button type="submit" class="btn btn-danger">Search</button>
        </div>
      </form>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-danger text-white text-center py-3 mt-auto">
    <p class="mb-0">&copy; 2025 Blood Bank. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
