<?php
session_start();
require_once 'koneksi.php'; 

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $confirm  = trim($_POST['confirm'] ?? '');

  if ($username === '' || $email === '' || $password === '' || $confirm === '') {
    $error = "Semua kolom wajib diisi.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Format email tidak valid.";
  } elseif ($password !== $confirm) {
    $error = "Password dan konfirmasi tidak cocok.";
  } else {
    $check = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    if (!$check) {
      $error = "Kesalahan saat pengecekan: " . mysqli_error($koneksi);
    } elseif (mysqli_num_rows($check) > 0) {
      $error = "Username atau email sudah digunakan.";
    } else {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $insert = mysqli_query($koneksi, "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashedPassword', 'user')");

      if ($insert) {
        $success = "Registrasi berhasil. <a href='login.php'>";
      } else {
        $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Registrasi - Legacy Sportwear</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="card-title text-center mb-4">Registrasi</h4>

            <?php if ($error): ?>
              <div class="alert alert-danger"><?= $error ?></div>
            <?php elseif ($success): ?>
              <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="post">
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" name="confirm" required>
              </div>

              <button type="submit" class="btn btn-success w-100">Daftar</button>
            </form>

            <div class="mt-3 text-center small">
              Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
