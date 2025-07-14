<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}
require_once '../koneksi.php';

// Tambah produk baru
if (isset($_POST['add_product'])) {
    $type = $_POST['type'];
    $name = $_POST['name'];
    $team = $_POST['team'] ?? null;
    $category = $_POST['category'] ?? null;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $description = $_POST['description'];
    $sizes = $_POST['sizes'] ?? null;
    $colors = $_POST['colors'] ?? null;
    $image = $_POST['image'];

    if ($type === 'jersey') {
        $koneksi->query("INSERT INTO jerseys (name, team, price, description, sizes, image) 
                         VALUES ('$name', '$team', '$price', '$description', '$sizes', '$image')");
        $jersey_id = $koneksi->insert_id;

        if (!empty($sizes)) {
            $sizesArray = explode(",", $sizes);
            foreach ($sizesArray as $size) {
                $size = trim($size);
                $koneksi->query("INSERT INTO jersey_sizes (jersey_id, size) VALUES ('$jersey_id', '$size')");
            }
        }
    } else {
        $koneksi->query("INSERT INTO accessories (name, category, price, description, colors, image) 
                         VALUES ('$name', '$category', '$price', '$description', '$colors', '$image')");
        $accessory_id = $koneksi->insert_id;

        if (!empty($colors)) {
            $colorsArray = explode(",", $colors);
            foreach ($colorsArray as $color) {
                $color = trim($color);
                $koneksi->query("INSERT INTO accessory_colors (accessory_id, color) VALUES ('$accessory_id', '$color')");
            }
        }
    }
}

// Hapus produk
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $id = $_GET['delete'];
    $type = $_GET['type'];

    if ($type === 'jersey') {
        $koneksi->query("DELETE FROM jersey_sizes WHERE jersey_id = $id");
        $koneksi->query("DELETE FROM jerseys WHERE id=$id");
    } else {
        $koneksi->query("DELETE FROM accessory_colors WHERE accessory_id = $id");
        $koneksi->query("DELETE FROM accessories WHERE id=$id");
    }
}

// Ambil data jersey + ukuran
$jerseys = $koneksi->query("
    SELECT j.*, GROUP_CONCAT(js.size ORDER BY js.size SEPARATOR ', ') AS all_sizes
    FROM jerseys j
    LEFT JOIN jersey_sizes js ON j.id = js.jersey_id
    GROUP BY j.id
    ORDER BY j.id ASC
");

// Ambil data accessories + warna
$accessories = $koneksi->query("
    SELECT a.*, GROUP_CONCAT(ac.color ORDER BY ac.color SEPARATOR ', ') AS all_colors
    FROM accessories a
    LEFT JOIN accessory_colors ac ON a.id = ac.accessory_id
    GROUP BY a.id
    ORDER BY a.id ASC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Kelola Produk</h2>
    <a href="admin_dashbord.php" class="btn btn-secondary">← Kembali ke Dashboard</a>
  </div>

  <!-- Form Tambah Produk -->
  <div class="card mb-4">
    <div class="card-header">Tambah Produk Baru</div>
    <div class="card-body">
      <form method="post">
        <div class="row g-3">
          <div class="col-md-4">
            <label>Jenis Produk</label>
            <select name="type" class="form-select" required>
              <option value="jersey">Jersey</option>
              <option value="accessory">Aksesori</option>
            </select>
          </div>
          <div class="col-md-4">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label>Harga</label>
            <input type="number" name="price" class="form-control" required step="0.01" min="0">
          </div>
        </div>

        <div class="row g-3 mt-3">
          <div class="col-md-6">
            <label>Team / Kategori</label>
            <input type="text" name="team" class="form-control" placeholder="Team untuk Jersey / Kategori untuk Aksesori">
            <input type="text" name="category" class="form-control mt-2" placeholder="Kategori aksesori">
          </div>
          <div class="col-md-6">
            <label>Ukuran / Warna</label>
            <input type="text" name="sizes" class="form-control" placeholder="Contoh: S,M,L">
            <input type="text" name="colors" class="form-control mt-2" placeholder="Contoh: Merah,Biru">
          </div>
        </div>

        <div class="mt-3">
          <label>Deskripsi</label>
          <textarea name="description" class="form-control" rows="2"></textarea>
        </div>

        <div class="mt-3">
          <label>Link Gambar Utama (contoh: image/barca1.png)</label>
          <input type="text" name="image" class="form-control" required>
        </div>

        <button class="btn btn-success mt-3" name="add_product">Tambah Produk</button>
      </form>
    </div>
  </div>

  <!-- Tabel Jerseys -->
  <div class="card mb-4">
    <div class="card-header bg-primary text-white">Daftar Jersey</div>
    <div class="card-body p-0">
      <table class="table table-bordered table-striped mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Team</th>
            <th>Harga</th>
            <th>Ukuran</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($j = $jerseys->fetch_assoc()): ?>
          <tr>
            <td><?= $j['id'] ?></td>
            <td><?= $j['name'] ?></td>
            <td><?= $j['team'] ?></td>
            <td>Rp<?= number_format($j['price'], 2, ',', '.') ?></td>
            <td><?= $j['all_sizes'] ?></td>
            <td><img src="../<?= $j['image'] ?>" alt="Jersey" width="80"></td>
            <td>
              <a href="?delete=<?= $j['id'] ?>&type=jersey" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Tabel Accessories -->
  <div class="card">
    <div class="card-header bg-secondary text-white">Daftar Aksesori</div>
    <div class="card-body p-0">
      <table class="table table-bordered table-striped mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Warna</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($a = $accessories->fetch_assoc()): ?>
          <tr>
            <td><?= $a['id'] ?></td>
            <td><?= $a['name'] ?></td>
            <td><?= $a['category'] ?></td>
            <td>Rp<?= number_format($a['price'], 2, ',', '.') ?></td>
            <td><?= $a['all_colors'] ?: '-' ?></td>
            <td><img src="../<?= $a['image'] ?>" alt="Aksesori" width="80"></td>
            <td>
              <a href="?delete=<?= $a['id'] ?>&type=accessory" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
