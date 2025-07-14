<?php
session_start();
require_once 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($username === '' || $password === '') {
    $error = "Username dan password wajib diisi.";
  } else {
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");
    
    if ($query && mysqli_num_rows($query) === 1) {
      $user = mysqli_fetch_assoc($query);

      if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
          header("Location: admin/admin_dashbord.php");
        } else {
          header("Location: index.php");
        }
        exit();
      } else {
        $error = "Password salah.";
      }
    } else {
      $error = "Username tidak ditemukan.";
    }
  }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Legacy Sportwear</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow">
          <div class="card-body">
            <h4 class="card-title text-center mb-4">Login</h4>

            <?php if ($error): ?>
              <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
              </div>

              <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="mt-3 text-center small">
              Belum punya akun? <a href="register.php">Daftar di sini</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
