<?php
session_start();

// Dummy login check
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Admin login
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['role'] = 'admin';
        header("Location: admin_dashboard.php");
        exit;
    } 
    // User login
    elseif ($username === 'user' && $password === 'user123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['role'] = 'user';
        header("Location: user_dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2 class="text-center mb-4">Login</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger text-center w-100"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
        <form method="POST" class="border p-4 rounded shadow" style="width: 100%; max-width: 400px;">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="admin or user" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="admin123 or user123" required>
            </div>
            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
