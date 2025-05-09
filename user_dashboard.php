<?php
session_start();
// Redirect if not logged in as a user
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

// Sample data: Only if blood_banks is not already set in the session
if (!isset($_SESSION['blood_banks'])) {
    $_SESSION['blood_banks'] = [
        [
            'name' => 'City Blood Bank',
            'location' => 'Dhaka',
            'contact' => '0123456789',
            'blood_type' => 'A+',
            'availability' => 'available'
        ],
        [
            'name' => 'Red Cross',
            'location' => 'Chittagong',
            'contact' => '9876543210',
            'blood_type' => 'O-',
            'availability' => 'unavailable'
        ],
        [
            'name' => 'Lifesaver Blood Bank',
            'location' => 'Khulna',
            'contact' => '01122334455',
            'blood_type' => 'B+',
            'availability' => 'available'
        ],
        [
            'name' => 'Hope Blood Center',
            'location' => 'Rajshahi',
            'contact' => '01888887777',
            'blood_type' => 'AB-',
            'availability' => 'available'
        ]
    ];
}

// Handle logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}


// Search handling
$bloodBanks = $_SESSION['blood_banks'] ?? [];
$searchResults = $bloodBanks;

if (isset($_GET['blood_type']) || isset($_GET['location'])) {
    $bt = $_GET['blood_type'] ?? '';
    $loc = $_GET['location'] ?? '';

    $searchResults = array_filter($bloodBanks, function ($bank) use ($bt, $loc) {
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
  <link rel="icon" href="./image/image.png" type="image/png" />
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

    <!-- Search Form -->
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

    <!-- Results Table -->
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
