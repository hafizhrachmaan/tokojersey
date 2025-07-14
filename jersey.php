<?php
session_start();
$pageTitle = "Jerseys - Jersey Store";
require_once 'koneksi.php';

$jerseys = [];
$query = mysqli_query($koneksi, "SELECT * FROM jerseys");
while ($row = mysqli_fetch_assoc($query)) {
    $jersey_id = $row['id'];

    $gallery = [];
    $resG = mysqli_query($koneksi, "SELECT image_url FROM jersey_gallery_images WHERE jersey_id = $jersey_id");
    while ($g = mysqli_fetch_assoc($resG)) {
        $gallery[] = $g['image_url'];
    }

    $sizes = [];
    $resS = mysqli_query($koneksi, "SELECT size FROM jersey_sizes WHERE jersey_id = $jersey_id");
    while ($s = mysqli_fetch_assoc($resS)) {
        $sizes[] = $s['size'];
    }

    $colors = [];
    $resC = mysqli_query($koneksi, "SELECT color FROM jersey_colors WHERE jersey_id = $jersey_id");
    while ($c = mysqli_fetch_assoc($resC)) {
        $colors[] = $c['color'];
    }

    $row['gallery_images'] = $gallery;
    $row['sizes'] = $sizes;
    $row['colors'] = $colors;

    $jerseys[] = $row;
}

$currentPage = basename($_SERVER['PHP_SELF']);

$teams = array_unique(array_column($jerseys, 'team'));
sort($teams);

$allSizes = array_reduce(array_column($jerseys, 'sizes'), 'array_merge', []);
$sizes = array_unique($allSizes);
sort($sizes);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php require_once 'navbar.php'; ?>

<main>
  <div class="container py-5">
    <div class="mb-4">
      <h1 class="fw-bold mb-2">Football Jerseys</h1>
      <p class="text-muted">Authentic jerseys from top teams around the world</p>
    </div>

    <div class="bg-light p-4 rounded mb-5">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label fw-medium">Tim</label>
          <select class="form-select" id="filterTeam">
            <option value="">Semua Tim</option>
            <?php foreach ($teams as $team): ?>
              <option><?php echo htmlspecialchars($team); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-medium">ukuran</label>
          <select class="form-select" id="filterSize">
            <option value="">Semua Ukuran</option>
            <?php foreach ($sizes as $size): ?>
              <option><?php echo htmlspecialchars($size); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-medium">Price Range</label>
          <select class="form-select" id="filterPrice">
            <option value="">Semua Harga</option>
            <option>Di Bawah Rp75.000</option>
            <option>Rp75.000 - Rp150.000</option>
            <option>Rp150.000 - Rp250.000</option>
            <option>Di Atas Rp250.000</option>
          </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button class="btn btn-primary w-100" id="applyFiltersBtn">Terapkan Filter</button>
        </div>
      </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
      <?php foreach ($jerseys as $jersey): ?>
        <div class="col">
          <div class="card h-100 shadow-sm product-card" data-bs-toggle="modal" data-bs-target="#jerseyModal" data-jersey-id="<?php echo $jersey['id']; ?>">
            <div class="position-relative">
              <img src="<?php echo htmlspecialchars($jersey['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($jersey['alt']); ?>">
              <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle p-1 favorite-btn">
                <i class="bi bi-heart"></i>
              </button>
            </div>
            <div class="card-body">
              <h5 class="card-title fw-semibold"><?php echo htmlspecialchars($jersey['team']); ?></h5>
              <p class="card-text text-muted"><?php echo htmlspecialchars($jersey['name']); ?></p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-primary fs-5">Rp<?php echo number_format($jersey['price'], 0, ',', '.'); ?></span>
                <button class="btn btn-primary rounded-circle"
                  data-bs-toggle="modal"
                  data-bs-target="#jerseyModal"
                  data-jersey-id="<?php echo $jersey['id']; ?>">
                  <i class="bi bi-cart"></i>
                </button>
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
        <p class="text-muted">KYour one-stop shop for authentic sports jerseys and accessories..</p>
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
          <input type="email" class="form-control" placeholder="Your email">
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
  const jerseyData = <?php echo json_encode($jerseys); ?>
</script>
<script src="js/script.js"></script>
<?php include 'modal_jersey.php'; ?>

</body>
</html>
