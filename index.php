<?php
require_once 'koneksi.php';
session_start();
$pageTitle = "Jersey Store - Premium Sports Jerseys & Accessories";
$testimonials = [];


// $featuredProducts = [
//   [
//     'name' => 'Premium Jersey',
//     'description' => 'Official Jersey Retro',
//     'price' => '94.99',
//     'image' => 'image/L1.png',
//     'link' => 'jersey.php'
    
//   ],

//   [
//     'name' => 'Premium Jersey',
//     'description' => 'Official Jersey Retro',
//     'price' => '94.99',
//     'image' => 'image/bayern1.png',
//     'link' => 'jersey.php'
//   ],
//   [
//     'name' => 'Premium Accessories',
//     'description' => 'Official Team Accessories',
//     'price' => '150.00',
//     'image' => 'image/madrid1.png',
//     'link' => 'jersey.php'
//   ],
//   [
//     'name' => 'Premium Accessories',
//     'description' => 'Official Team Accessories',
//     'price' => '37.79',
//     'image' => 'image/milan.png',
//     'link' => 'accessories.php'
//   ]
// ];

// $testimonials = [
//   [
//     'quote' => 'saya sangat puas dengan produk yang saya beli. Kualitasnya sangat baik dan sesuai dengan deskripsi di website.',
//     'author' => 'Iqbal Habibi',
//     'rating' => 5
//   ],
//   [
//     'quote' => 'Saya telah memesan beberapa kali dan tidak pernah kecewa. Produk yang ditawarkan asli dengan harga yang sangat terjangkau.',
//     'author' => 'Rizki Alvian',
//     'rating' => 5
//   ],
//   [
//     'quote' => 'Pilihan aksesori yang sangat beragam. Pengirimannya sedikit terlambat, tetapi produk ini benar-benar sepadan dengan penantiannya',
//     'author' => 'Hegel Pidela',
//     'rating' => 4
//   ]
// ];

// // Ambil produk
// $featuredProducts = [];
// $resultProducts = mysqli_query($koneksi, "SELECT * FROM products");
// while ($row = mysqli_fetch_assoc($resultProducts)) {
//     $featuredProducts[] = $row;
// }

$testimonials = [];
$resultTestimonials = mysqli_query($koneksi, "SELECT * FROM testimonials");
while ($row = mysqli_fetch_assoc($resultTestimonials)) {
    $testimonials[] = $row;
}



$featuredJerseys = [];
$featuredAccessories = [];

$resultJersey = mysqli_query($koneksi, "SELECT id, name, team AS brand, price, image, 'jersey' AS type FROM jerseys WHERE is_featured = 1 LIMIT 2");
while ($row = mysqli_fetch_assoc($resultJersey)) {
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

    $row['gallery_images'] = $gallery;
    $row['sizes'] = $sizes;

    $featuredJerseys[] = $row;
}


$resultAccessories = mysqli_query($koneksi, "SELECT id, name, category AS brand, price, image, 'accessory' AS type FROM accessories WHERE is_featured = 1 LIMIT 2");
while ($row = mysqli_fetch_assoc($resultAccessories)) {
    $accessory_id = $row['id'];

    $gallery = [];
    $resG = mysqli_query($koneksi, "SELECT image_path FROM accessory_gallery_images WHERE accessory_id = $accessory_id");
    while ($g = mysqli_fetch_assoc($resG)) {
        $gallery[] = $g['image_path'];
    }

    $colors = [];
    $resC = mysqli_query($koneksi, "SELECT color FROM accessory_colors WHERE accessory_id = $accessory_id");
    while ($c = mysqli_fetch_assoc($resC)) {
        $colors[] = $c['color'];
    }

    $row['gallery_images'] = $gallery;
    $row['colors'] = $colors;

    $featuredAccessories[] = $row;
}



$featuredProducts = array_merge($featuredJerseys, $featuredAccessories);



$currentPage = basename($_SERVER['PHP_SELF']);

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
<?php
require_once 'navbar.php';
?>
            


  <main>
    <section class="hero-section py-5">
      <div class="container py-5">
        <div class="row align-items-center">
          <div class="col-lg-6 mb-5 mb-lg-0">
            <h1 class="display-4 fw-bold mb-4">Premium Sports Jersey & Accessories</h1>
            <p class="lead mb-4">
              Get the latest jersey from your favorite team and players with premium quality and authentic design.
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3">
              <a href="jersey.php" class="btn btn-light text-primary fw-bold px-4 py-3">Shop Jerseys</a>
              <a href="accessories.php" class="btn btn-outline-light fw-bold px-4 py-3">Shop Accessories</a>
            </div>
          </div>
          <div class="col-lg-6">
            <img src="image/w1.png" class="img-fluid rounded shadow">
          </div>
        </div>
      </div>
    </section>

    <section class="py-5">
      <div class="container py-4">
        <h2 class="text-center fw-bold mb-5">Special Products</h2>
        <div class="row g-4">
          <div class="col-md-6">
            <div class="position-relative overflow-hidden rounded shadow category-card">
              <img src="image/barca1.png" alt="Jerseys" class="w-100 category-img">
              <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white category-overlay">
                <h3 class="fw-bold mb-2">Jerseys</h3>
                <p class="mb-3">Authentic jerseys from top leagues around the world</p>
                <a href="jersey.php" class="btn btn-light text-primary fw-bold">View Collection</a>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="position-relative overflow-hidden rounded shadow category-card">
              <img src="image/juv.jpeg" alt="Accessories" class="w-100 category-img">
              <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white category-overlay">
                <h3 class="fw-bold mb-2">Accessories</h3>
                <p class="mb-3">Complete your look with our premium sports accessories</p>
                <a href="accessories.php" class="btn btn-light text-primary fw-bold">View Collection</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-5 featured-products">
      <div class="container py-4">
        <h2 class="text-center fw-bold mb-5">Featured Products</h2>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
  <?php foreach ($featuredProducts as $idx => $product): ?>
    <div class="col">
      <div class="card h-100 shadow-sm product-card featured-product-card"
     style="cursor:pointer;"
     data-bs-toggle="modal"
     data-bs-target="#<?php echo $product['type'] === 'jersey' ? 'jerseyModal' : 'accessoryModal'; ?>"
     data-<?php echo $product['type']; ?>-id="<?php echo $product['id']; ?>"
     data-featured-idx="<?php echo $idx; ?>">

        <a href="#" class="text-decoration-none">
          <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
          <div class="card-body">
            <h5 class="card-title fw-semibold text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
            <p class="card-text text-muted">
              <?= isset($product['description']) ? htmlspecialchars($product['description']) : ''; ?>
            </p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold text-primary fs-5">
                Rp<?php echo number_format($product['price'], 0, ',', '.'); ?>
              </span>
              <button class="btn btn-primary rounded-circle" type="button">
                <i class="bi bi-cart"></i>
              </button>
            </div>
          </div>
        </a>
      </div>
    </div>
  <?php endforeach; ?>
</div>




        </div>
      </div>
    </section>

    <section class="py-5">
      <div class="container py-4">
        <h2 class="text-center fw-bold mb-5">Reviews and Ratings</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
          <?php foreach ($testimonials as $testimonial): ?>
            <div class="col">
              <div class="card h-100 shadow-sm p-4 testimonial-card">
                <div class="mb-3">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="bi <?php echo ($i <= $testimonial['rating']) ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                  <?php endfor; ?>
                </div>
                <p class="card-text text-muted mb-4">"<?php echo htmlspecialchars($testimonial['quote']); ?>"</p>
                <p class="fw-semibold mb-0"><?php echo htmlspecialchars($testimonial['author']); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- <div class="modal fade" id="featuredProductModal" tabindex="-1" aria-labelledby="featuredProductModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header border-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-4 mb-md-0">
                <img id="featuredModalImage" src="https://placehold.co/600x600/eee/ccc?text=Product" class="img-fluid rounded" alt="Product Preview">
              </div>
              <div class="col-md-6">
                <h2 id="featuredModalName" class="fw-bold mb-1">Product Name</h2>
                <h3 id="featuredModalDesc" class="text-muted mb-4">Product Description</h3>
                <p id="featuredModalPrice" class="fs-4 fw-bold text-primary mb-4">$00.00</p>
                <div class="d-flex gap-3 p-5">
                  <button class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-cart"></i> Add to Cart
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main> -->

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
    const featuredProducts = <?php echo json_encode($featuredProducts); ?>;
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.featured-product-card').forEach(function(card, idx) {
        card.addEventListener('click', function(e) {
          e.preventDefault();
          const product = featuredProducts[idx];
          document.getElementById('featuredModalImage').src = product.image;
          document.getElementById('featuredModalImage').alt = product.name;
          document.getElementById('featuredModalName').textContent = product.name;
          document.getElementById('featuredModalDesc').textContent = product.description;
          document.getElementById('featuredModalPrice').textContent = '$' + product.price;
          var modal = new bootstrap.Modal(document.getElementById('featuredProductModal'));
          modal.show();
        });
      });
    });
  </script>
  <script>
  const jerseyData = <?php echo json_encode($featuredJerseys); ?>;
  const accessoryData = <?php echo json_encode($featuredAccessories); ?>;
</script>

  <script src="js/script.js"></script>
  <?php include 'modal_jersey.php'; ?>
<?php include 'modal_accessories.php'; ?>

</body>
</html>