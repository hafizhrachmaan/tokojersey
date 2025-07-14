<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once '../koneksi.php';

// Hitung jumlah user dan produk
$totalUsers = 0;
$totalProducts = 0;

$userQuery = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM users");
if ($userQuery && $row = mysqli_fetch_assoc($userQuery)) {
    $totalUsers = $row['total'];
}

$productQuery = mysqli_query($koneksi, "SELECT (
    (SELECT COUNT(*) FROM jerseys) + (SELECT COUNT(*) FROM accessories)
) AS total");
if ($productQuery && $row = mysqli_fetch_assoc($productQuery)) {
    $totalProducts = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="mb-4">Dashboard Admin</h1>
    <p class="mb-5">Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>! Silakan pilih menu di bawah ini untuk mengelola sistem.</p>

    <div class="row g-4">
        <!-- Kelola Users -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Kelola Users</h5>
                    <p class="card-text">Total user terdaftar: <strong><?= $totalUsers ?></strong></p>
                    <a href="admin_user.php" class="btn btn-primary w-100">Kelola Users</a>
                </div>
            </div>
        </div>

        <!-- Kelola Produk -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Kelola Produk</h5>
                    <p class="card-text">Total produk tersedia: <strong><?= $totalProducts ?></strong></p>
                    <a href="admin_produk.php" class="btn btn-primary w-100">Kelola Produk</a>
                </div>
            </div>
        </div>

        <!-- Logout -->
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-danger">
                <div class="card-body">
                    <h5 class="card-title">Logout</h5>
                    <p class="card-text">Keluar dari halaman admin dan akhiri sesi.</p>
                    <a href="logout.php" class="btn btn-outline-danger w-100">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
