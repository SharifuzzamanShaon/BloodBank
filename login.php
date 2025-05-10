<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';
    
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['role'] = 'admin';
        echo json_encode(['redirect' => 'admin_dashboard.php']);
    } elseif ($username === 'user' && $password === 'user123') {
        $_SESSION['logged_in'] = true;
        $_SESSION['role'] = 'user';
        echo json_encode(['redirect' => 'user_dashboard.php']);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password!']);
    }
    exit;
}
?>


<!-- login.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <link rel="icon" href="./image/image.png" type="image/png" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="container mt-4">
  <h2 class="text-center mb-4">Login</h2>
  <img src="./image/image.png" alt="Blood Bank" class="d-block mx-auto mb-2" width="100" height="100" />
  
  <div id="error-msg" class="alert alert-danger text-center d-none"></div>

  <div class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <form id="login-form" class="border p-4 rounded shadow" style="width: 100%; max-width: 400px;">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="admin or user" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="admin123 or user123" required />
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Login</button>
      </div>
    </form>
  </div>
  <script>
    const form = document.getElementById("login-form");
    const errorMsg = document.getElementById("error-msg");

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = {
        username: form.username.value,
        password: form.password.value,
      };

      try {
        const res = await fetch("login.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
        });

        const result = await res.json();

        if (!res.ok) throw new Error(result.error || "Login failed");

        window.location.href = result.redirect;
      } catch (err) {
        errorMsg.textContent = err.message;
        errorMsg.classList.remove("d-none");
      }
    });
  </script>
</body>
</html>

