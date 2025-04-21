<?php
session_start();

// Handle logout request
if (isset($_POST['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login page
    exit;
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Handle blood bank search
$searchResults = $_SESSION['blood_banks'];
if (isset($_GET['blood_type']) || isset($_GET['location'])) {
    $bt = $_GET['blood_type'] ?? '';
    $loc = $_GET['location'] ?? '';

    $searchResults = array_filter($_SESSION['blood_banks'], function ($bank) use ($bt, $loc) {
        $matchesBT = $bt === '' || $bank['blood_type'] === $bt;
        $matchesLoc = $loc === '' || stripos($bank['location'], $loc) !== false;
        return $matchesBT && $matchesLoc;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Blood Bank</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Blood Bank Search</h2>
        <form method="POST">
            <button type="submit" name="logout" class="btn btn-outline-danger">Logout</button>
        </form>
    </div>

    <!-- User Search Form -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="location" class="form-control" placeholder="Search by location" value="<?= htmlspecialchars($_GET['location'] ?? '') ?>">
        </div>
        <div class="col-md-4">
            <select name="blood_type" class="form-select">
                <option value="">All Blood Types</option>
                <?php
                $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                foreach ($bloodTypes as $bt):
                    $selected = ($_GET['blood_type'] ?? '') === $bt ? 'selected' : '';
                    echo "<option value='$bt' $selected>$bt</option>";
                endforeach;
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-success w-100">Search</button>
        </div>
    </form>

    <!-- Blood Bank Table -->
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th>Contact</th>
            <th>Blood Type</th>
            <th>Availability</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($searchResults)): ?>
            <tr><td colspan="5" class="text-center">No results found</td></tr>
        <?php else: ?>
            <?php foreach ($searchResults as $bank): ?>
                <tr>
                    <td><?= htmlspecialchars($bank['name']) ?></td>
                    <td><?= htmlspecialchars($bank['location']) ?></td>
                    <td><?= htmlspecialchars($bank['contact']) ?></td>
                    <td><?= htmlspecialchars($bank['blood_type']) ?></td>
                    <td>
                        <span class="badge <?= $bank['availability'] === 'available' ? 'bg-success' : 'bg-danger' ?>">
                            <?= ucfirst($bank['availability']) ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
