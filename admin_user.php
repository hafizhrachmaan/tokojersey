<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}
require_once '../koneksi.php';

// Tambah user
if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    $koneksi->query($sql);
}

// Hapus user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $koneksi->query("DELETE FROM users WHERE id_user=$id");
}

// Edit user
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $koneksi->query("UPDATE users SET username='$username', email='$email', password='$password' WHERE id_user=$id");
    } else {
        $koneksi->query("UPDATE users SET username='$username', email='$email' WHERE id_user=$id");
    }
}

// Ambil semua user
$result = $koneksi->query("SELECT * FROM users ORDER BY tanggal_dibuat ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Kelola Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h2 class="mb-4">Kelola Users</h2>

    <!-- Tombol Kembali ke Dashboard -->
    <a href="admin_dashbord.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    <!-- Form Tambah User -->
    <div class="card mb-4">
        <div class="card-header">Tambah User Baru</div>
        <div class="card-body">
            <form method="post">
                <div class="mb-2">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary" name="add">Tambah</button>
            </form>
        </div>
    </div>

    <!-- Tabel Users -->
    <div class="card">
        <div class="card-header">Daftar Users</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_user'] ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= $row['tanggal_dibuat'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_user'] ?>">Edit</button>
                            <a href="?delete=<?= $row['id_user'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')">Hapus</a>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $row['id_user'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User ID <?= $row['id_user'] ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $row['id_user'] ?>">
                                        <div class="mb-2">
                                            <label>Username</label>
                                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($row['username']) ?>" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Password Baru (Opsional)</label>
                                            <input type="password" name="password" class="form-control">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti password.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-primary" name="edit">Simpan</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
