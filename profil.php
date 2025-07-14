<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$fullname = $_SESSION['fullname'] ?? 'Belum diisi';
$email = $_SESSION['email'] ?? 'Belum diisi';
$phone = $_SESSION['phone'] ?? 'Belum diisi';
$address = $_SESSION['address'] ?? 'Belum diisi';
$joined = $_SESSION['joined'] ?? '2024-01-01';
$profile_picture = $_SESSION['profile_picture'] ?? 'https://via.placeholder.com/100';
$status = $_SESSION['status'] ?? 'Aktif';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Pengguna - Legacy Sportwear</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .profile-pic {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 50%;
    }
  </style>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow">
        <div class="card-body text-center">
          <img src="<?= htmlspecialchars($profile_picture) ?>" alt="Foto Profil" class="profile-pic mb-3">
          <h4 class="card-title mb-3"><?= htmlspecialchars($fullname) ?></h4>

          <p><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
          <p><strong>Nomor Telepon:</strong> <?= htmlspecialchars($phone) ?></p>
          <p><strong>Alamat:</strong> <?= htmlspecialchars($address) ?></p>
          <p><strong>Tanggal Bergabung:</strong> <?= htmlspecialchars($joined) ?></p>
          <p><strong>Status Akun:</strong> <?= htmlspecialchars($status) ?></p>
          <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>

          <div class="mt-4 d-grid gap-2">
            <a href="edit_profile.php" class="btn btn-primary">Edit Profil</a>
            <a href="order_history.php" class="btn btn-info">Lihat Histori Pesanan</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
            <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
