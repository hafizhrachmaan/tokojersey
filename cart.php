<?php
session_start();
require_once 'koneksi.php';

$pageTitle = "Keranjang Belanja - JerseyStore";
$currentPage = basename($_SERVER['PHP_SELF']);

$session_id = session_id();
$cart_items = [];
$grand_total = 0;


$query = "SELECT * FROM cart WHERE session_id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    if ($row['product_type'] === 'jersey') {
        $product_query = "SELECT id, name, team AS brand, price, image FROM jerseys WHERE id = ?";
    } else {
        $product_query = "SELECT id, name, category AS brand, price, image FROM accessories WHERE id = ?";
    }

    $stmt2 = $koneksi->prepare($product_query);
    $stmt2->bind_param("s", $row['product_id']);
    $stmt2->execute();
    $product_result = $stmt2->get_result();
    $product = $product_result->fetch_assoc();

    if ($product) {
        $product['quantity'] = $row['quantity'];
        $product['size'] = $row['size'];
        $product['subtotal'] = $row['quantity'] * $product['price'];
        $product['product_type'] = $row['product_type'];
        $product['cart_id'] = $row['id'];

        $grand_total += $product['subtotal'];
        $cart_items[] = $product;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>

<?php require_once 'navbar.php'; ?>

<main>
  <div class="container py-5">
    <h1 class="fw-bold mb-4">Keranjang Belanja Anda</h1>

    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th scope="col" colspan="2" class="ps-0">Produk</th>
            <th scope="col">Harga</th>
            <th scope="col" class="text-center">Jumlah</th>
            <th scope="col" class="text-end">Subtotal</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart_items as $item): ?>
            <tr>
              <td style="width: 100px;" class="ps-0">
                <img src="<?php echo htmlspecialchars($item['image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['name']); ?>">
              </td>
              <td>
                <?php echo htmlspecialchars($item['name']); ?><br>
                <?php if ($item['product_type'] === 'jersey'): ?>
                  <small class="text-muted">ukuran: <strong><?php echo htmlspecialchars($item['size']); ?></strong></small>
                <?php endif; ?>
              </td>
              <td>Rp<?php echo number_format($item['price'], 0, ',', '.'); ?></td>
              <td class="text-center">
                <form action="update_cart.php" method="post" class="d-flex justify-content-center align-items-center">
                  <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                  <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control form-control-sm" style="width: 70px;">
                  <button type="submit" class="btn btn-sm btn-outline-primary ms-2" title="Update jumlah">
                    <i class="bi bi-arrow-repeat"></i>
                  </button>
                </form>
              </td>
              <td class="text-end">Rp<?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
              <td class="text-end">
                <a href="remove_cart.php?cart_id=<?php echo $item['cart_id']; ?>"
                   class="btn btn-sm btn-outline-danger rounded-circle p-1"
                   style="width: 32px; height: 32px; display: flex; justify-content: center; align-items: center;"
                   title="Hapus item">
                  <span style="font-size: 20px; line-height: 1;">&times;</span>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="row justify-content-end mt-4">
      <div class="col-md-5 col-lg-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title fw-bold d-flex justify-content-between">
              <span>Total Belanja</span>
              <span class="text-primary">Rp<?php echo number_format($grand_total, 0, ',', '.'); ?></span>
            </h5>
            <div class="d-grid gap-2 mt-4">
              <a href="checkout.php" class="btn btn-primary">Lanjut ke Checkout</a>
              <a href="remove_cart.php?clear_all=true" class="btn btn-outline-danger">Kosongkan Keranjang</a>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<footer class="pt-5 pb-3">
  <div class="container">
    <div class="border-top border-secondary pt-4 text-center text-muted">
      <p>&copy; <?php echo date("Y"); ?> Legacy Sportwear. All rights reserved.</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>

</body>
</html>
