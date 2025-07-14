<?php
require_once 'koneksi.php';

$pageTitle = "Accessories";
$accessoryDescription = "A collection of stylish accessories that combine comfort and functionality.";

function formatRupiah($angka) {
    return "Rp" . number_format($angka, 0, ',', '.');
}

$accessories = [];

$queryAccessories = mysqli_query($koneksi, "SELECT * FROM accessories");
while ($row = mysqli_fetch_assoc($queryAccessories)) {
    $id = $row['id'];

    $galleryResult = mysqli_query($koneksi, "SELECT image_path FROM accessory_gallery_images WHERE accessory_id = $id");
    $gallery = [];
    while ($g = mysqli_fetch_assoc($galleryResult)) {
        $gallery[] = $g['image_path'];
    }

    $colorResult = mysqli_query($koneksi, "SELECT color FROM accessory_colors WHERE accessory_id = $id");
    $colors = [];
    while ($c = mysqli_fetch_assoc($colorResult)) {
        $colors[] = $c['color'];
    }

    $row['gallery_images'] = $gallery;
    $row['colors'] = $colors;

    $accessories[] = $row;
}

$currentPage = basename($_SERVER['PHP_SELF']);
$categories = array_unique(array_column($accessories, 'category'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $pageTitle; ?> - Jersey Store</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<?php require_once 'navbar.php'; ?>

<main>
  <div class="container py-5">
    <div class="mb-4">
      <h1 class="fw-bold mb-2">Sports Accessories</h1>
      <p class="text-muted">Complete your sports gear with our premium accessories</p>
    </div>

    <div class="bg-light p-4 rounded mb-5">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-medium">Kategori</label>
          <select class="form-select" id="filterCategory">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $category): ?>
              <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-medium">Rentang Harga</label>
          <select class="form-select" id="filterPrice">
            <option value="">Semua Harga</option>
            <option>Di bawah Rp20.000</option>
            <option>Rp20.000 - Rp30.000</option>
            <option>Rp30.000 - Rp50.000</option>
            <option>Di atas Rp50.000</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-medium">Urutkan</label>
          <select class="form-select" id="filterSort">
            <option value="featured">Unggulan</option>
            <option value="lowToHigh">Harga: Rendah ke Tinggi</option>
            <option value="highToLow">Harga: Tinggi ke Rendah</option>
            <option value="newest">Terbaru</option>
          </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button class="btn btn-primary w-100" id="applyFiltersBtn">Terapkan Filter</button>
        </div>
      </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
      <?php foreach ($accessories as $accessory): ?>
        <div class="col">
          <div class="card h-100 shadow-sm product-card" data-bs-toggle="modal" data-bs-target="#accessoryModal" data-accessory-id="<?php echo $accessory['id']; ?>">
            <div class="position-relative">
              <img src="<?php echo htmlspecialchars($accessory['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($accessory['name']); ?>" />
              <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle p-1 favorite-btn">
                <i class="bi bi-heart"></i>
              </button>
            </div>
            <div class="card-body">
              <span class="badge bg-light text-dark mb-2"><?php echo htmlspecialchars($accessory['category']); ?></span>
              <h5 class="card-title fw-semibold"><?php echo htmlspecialchars($accessory['name']); ?></h5>
              <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-primary fs-5"><?php echo formatRupiah($accessory['price']); ?></span>
                <button class="btn btn-primary rounded-circle"><i class="bi bi-cart"></i></button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</main>

<footer class="pt-5 pb-3">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-lg-3 col-md-6">
        <h3 class="fs-4 fw-bold mb-3">Legacy Sportwear</h3>
        <p class="text-muted">Your one-stop shop for authentic sports jerseys and accessories.</p>
      </div>
      <div class="col-lg-3 col-md-6">
        <h4 class="fs-5 fw-semibold mb-3">Shop</h4>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="jersey.php">Jerseys</a></li>
          <li class="mb-2"><a href="accessories.php">Accessories</a></li>
          <li class="mb-2"><a href="jersey.php">New Arrivals</a></li>
          <li class="mb-2"><a href="jersey.php">Sale</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6">
        <h4 class="fs-5 fw-semibold mb-3">Help</h4>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#">Contact Us</a></li>
          <li class="mb-2"><a href="#">Shipping</a></li>
          <li class="mb-2"><a href="#">Returns</a></li>
          <li class="mb-2"><a href="#">FAQ</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6">
        <h4 class="fs-5 fw-semibold mb-3">Newsletter</h4>
        <p class="text-muted mb-3">Subscribe to get special offers and updates.</p>
        <div class="input-group">
          <input type="email" class="form-control" placeholder="Your Email" />
          <button class="btn btn-primary" type="button">Subscribe</button>
        </div>
      </div>
    </div>
    <div class="border-top border-secondary pt-4 text-center text-muted">
      <p>&copy; <?php echo date("Y"); ?> Legacy Sportwear. All rights reserved.</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const accessoryData = <?php echo json_encode($accessories, JSON_PRETTY_PRINT); ?>;
</script>
<script src="js/script.js"></script>
<?php if (!empty($accessories)) include 'modal_accessories.php'; ?>
</body>
</html>
