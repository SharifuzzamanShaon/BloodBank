<?php include 'session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Info</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">

    <h2 class="mb-3">Blood Bank Information</h2>
    <a href="add.php" class="btn btn-primary mb-3">Add New Entry</a>

    <!-- Filter Options -->
    <div class="mb-3">
        <a href="index.php?filter=all" class="btn btn-secondary btn-sm">All</a>
        <a href="index.php?filter=available" class="btn btn-success btn-sm">Available</a>
        <a href="index.php?filter=not_available" class="btn btn-danger btn-sm">Not Available</a>
    </div>

    <!-- Blood Bank List Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Contact</th>
                <th>Blood Type</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Ensure session is initialized
            if (!isset($_SESSION['blood_banks'])) {
                $_SESSION['blood_banks'] = [];
            }

            // Filtering Logic
            $filter = $_GET['filter'] ?? 'all';
            $filteredBanks = [];

            foreach ($_SESSION['blood_banks'] as $bank) {
                if ($filter === 'all' || $bank['availability'] === $filter) {
                    $filteredBanks[] = $bank;
                }
            }

            // Display Blood Bank Info
            if (!empty($filteredBanks)): 
                foreach ($filteredBanks as $index => $bank): ?>
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
                        <td>
                            <a href="edit.php?index=<?= $index ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?index=<?= $index ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; 
            else: ?>
                <tr>
                    <td colspan="6" class="text-center">No blood bank records found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
