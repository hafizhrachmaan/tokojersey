<?php
session_start();
require_once 'koneksi.php';

$pageTitle = "Hasil Pencarian - Legacy Sportwear";
$currentPage = basename($_SERVER['PHP_SELF']);

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$products = [];
$jerseyData = [];
$accessoryData = [];

if (!empty($keyword)) {
    $likeKeyword = "%{$keyword}%";

    $stmtJersey = $koneksi->prepare("SELECT id, name, team AS brand, price, image, 'jersey' AS type FROM jerseys WHERE name LIKE ? OR team LIKE ?");
    $stmtJersey->bind_param("ss", $likeKeyword, $likeKeyword);
    $stmtJersey->execute();
    $resultJersey = $stmtJersey->get_result();

    while ($row = $resultJersey->fetch_assoc()) {
        $id = $row['id'];

        $stmtGallery = $koneksi->prepare("SELECT image_url FROM jersey_gallery_images WHERE jersey_id = ?");
        $stmtGallery->bind_param("i", $id);
        $stmtGallery->execute();
        $resGallery = $stmtGallery->get_result();
        $row['gallery_images'] = [];
        while ($g = $resGallery->fetch_assoc()) {
            $row['gallery_images'][] = $g['image_url'];
        }

        if (!empty($row['gallery_images'])) {
            $row['image'] = $row['gallery_images'][0];
        }

        $stmtSize = $koneksi->prepare("SELECT size FROM jersey_sizes WHERE jersey_id = ?");
        $stmtSize->bind_param("i", $id);
        $stmtSize->execute();
        $resSize = $stmtSize->get_result();
        $row['sizes'] = [];
        while ($s = $resSize->fetch_assoc()) {
            $row['sizes'][] = $s['size'];
        }

        $stmtDesc = $koneksi->prepare("SELECT description FROM jerseys WHERE id = ?");
        $stmtDesc->bind_param("i", $id);
        $stmtDesc->execute();
        $resDesc = $stmtDesc->get_result();
        $row['description'] = $resDesc->fetch_assoc()['description'] ?? '';

        $products[] = $row;
        $jerseyData[] = $row;
    }

    $stmtAcc = $koneksi->prepare("SELECT id, name, category AS brand, price, image, 'accessory' AS type FROM accessories WHERE name LIKE ? OR category LIKE ?");
    $stmtAcc->bind_param("ss", $likeKeyword, $likeKeyword);
    $stmtAcc->execute();
    $resultAcc = $stmtAcc->get_result();
    while ($row = $resultAcc->fetch_assoc()) {
        $id = $row['id'];

        $stmtGallery = $koneksi->prepare("SELECT image_path FROM accessory_gallery_images WHERE accessory_id = ?");
        $stmtGallery->bind_param("i", $id);
        $stmtGallery->execute();
        $resGallery = $stmtGallery->get_result();
        $row['gallery_images'] = [];
        while ($g = $resGallery->fetch_assoc()) {
            $row['gallery_images'][] = $g['image_path'];
        }

        if (!empty($row['gallery_images'])) {
            $row['image'] = $row['gallery_images'][0];
        }

        $stmtColor = $koneksi->prepare("SELECT color FROM accessory_colors WHERE accessory_id = ?");
        $stmtColor->bind_param("i", $id);
        $stmtColor->execute();
        $resColor = $stmtColor->get_result();
        $row['colors'] = [];
        while ($c = $resColor->fetch_assoc()) {
            $row['colors'][] = $c['color'];
        }

        $products[] = $row;
        $accessoryData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<?php require_once 'navbar.php'; ?>

<main>
  <div class="container py-5">
    <h1 class="fw-bold mb-4">Hasil Pencarian: <?= htmlspecialchars($keyword); ?></h1>

    <?php if (count($products) > 0): ?>
      <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-4">
        <?php foreach ($products as $item): ?>
          <div class="col">
            <div class="card h-100 shadow-sm product-card"
              data-bs-toggle="modal"
              data-bs-target="#<?= $item['type'] === 'jersey' ? 'jerseyModal' : 'accessoryModal'; ?>"
              data-<?= $item['type'] ?>-id="<?= $item['id']; ?>">
              <div class="position-relative">
                <img src="<?= htmlspecialchars($item['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']); ?>">
                <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle p-1 favorite-btn">
                  <i class="bi bi-heart"></i>
                </button>
              </div>
              <div class="card-body">
                <h5 class="card-title fw-semibold"><?= htmlspecialchars($item['brand']); ?></h5>
                <p class="card-text text-muted"><?= htmlspecialchars($item['name']); ?></p>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold text-primary fs-5">Rp<?= number_format($item['price'], 0, ',', '.'); ?></span>
                  <button class="btn btn-primary rounded-circle"
                          data-bs-toggle="modal"
                          data-bs-target="#<?= $item['type'] === 'jersey' ? 'jerseyModal' : 'accessoryModal'; ?>"
                          data-<?= $item['type'] ?>-id="<?= $item['id']; ?>">
                    <i class="bi bi-cart"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-warning">Produk tidak ditemukan untuk kata kunci "<?= htmlspecialchars($keyword); ?>".</div>
    <?php endif; ?>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const jerseyData = <?= json_encode(array_values($jerseyData)); ?>;
  const accessoryData = <?= json_encode(array_values($accessoryData)); ?>;
</script>

<script src="js/script.js"></script>

<?php if (!empty($jerseyData)) include 'modal_jersey.php'; ?>
<?php if (!empty($accessoryData)) include 'modal_accessories.php'; ?>

</body>
</html>
