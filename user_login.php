<?php
session_start();

// Sample users (username => password)
$users = [
    'admin' => 'admin123',
    'user' => 'user123',
];

// Handle login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['user'] = $username;
        header('Location: user_login.php');
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: user_login.php");
    exit;
}

// Fake data
if (!isset($_SESSION['blood_banks'])) {
    $_SESSION['blood_banks'] = [
        ['name' => 'LifeBlood Center', 'location' => 'Dhaka', 'contact' => '0123456', 'blood_type' => 'A+', 'availability' => 'available'],
        ['name' => 'Hope Blood Bank', 'location' => 'Chittagong', 'contact' => '9876543', 'blood_type' => 'B-', 'availability' => 'not_available'],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Blood Bank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<?php if (!isset($_SESSION['user'])): ?>
    <h2 class="text-center mb-4">User Login</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
        <form method="POST" class="border p-4 rounded shadow" style="width: 100%; max-width: 400px;">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>

<?php else: ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</h3>
        <a href="?logout=1" class="btn btn-danger btn-sm">Logout</a>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-4">
            <select name="blood_type" class="form-select">
                <option value="">All Blood Groups</option>
                <?php foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group): ?>
                    <option value="<?= $group ?>" <?= ($_GET['blood_type'] ?? '') === $group ? 'selected' : '' ?>><?= $group ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="location" class="form-control" placeholder="Search by location" value="<?= $_GET['location'] ?? '' ?>">
        </div>
        <div class="col-md-4 d-grid">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Blood Bank Inventory -->
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Contact</th>
                <th>Blood Type</th>
                <th>Availability</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $blood_type = $_GET['blood_type'] ?? '';
            $location = $_GET['location'] ?? '';

            $filtered = array_filter($_SESSION['blood_banks'], function ($b) use ($blood_type, $location) {
                return (!$blood_type || $b['blood_type'] === $blood_type) &&
                       (!$location || stripos($b['location'], $location) !== false);
            });

            if (!empty($filtered)):
                foreach ($filtered as $b): ?>
                    <tr>
                        <td><?= htmlspecialchars($b['name']) ?></td>
                        <td><?= htmlspecialchars($b['location']) ?></td>
                        <td><?= htmlspecialchars($b['contact']) ?></td>
                        <td><?= htmlspecialchars($b['blood_type']) ?></td>
                        <td>
                            <span class="badge <?= $b['availability'] === 'available' ? 'bg-success' : 'bg-danger' ?>">
                                <?= ucfirst($b['availability']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr>
                    <td colspan="5" class="text-center">No matching records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>
</body>
</html>
