<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$stmtUser = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
$stmtUser->bind_param("s", $username);
$stmtUser->execute();
$userResult = $stmtUser->get_result();
$user = $userResult->fetch_assoc();
$saldo = $user ? (int) $user['saldo'] : 0;

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
    $stmt2->bind_param("i", $row['product_id']);
    $stmt2->execute();
    $product_result = $stmt2->get_result();
    $product = $product_result->fetch_assoc();

    if ($product) {
        $product['quantity'] = $row['quantity'];
        $product['size'] = $row['size'];
        $product['subtotal'] = $row['quantity'] * $product['price'];
        $product['product_type'] = $row['product_type'];
        $cart_items[] = $product;
        $grand_total += $product['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout - JerseyStore</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php require_once 'navbar.php'; ?>

<div class="container py-5">
  <h2 class="mb-4">Checkout</h2>

  <div class="row">
    <div class="col-md-7">
      <div class="card p-4 mb-4">
        <h5 class="mb-3">Informasi Pembayaran</h5>

        <form id="checkoutForm" action="process_checkout.php" method="post">
          <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="fullName" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="address" class="form-control" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Kota</label>
              <input type="text" name="city" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Kode Pos</label>
              <input type="text" name="zip" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Negara</label>
            <select name="country" class="form-select" required>
              <option value="">Pilih Negara</option>
              <option>Indonesia</option>
              <option>Malaysia</option>
              <option>Singapura</option>
              <option>Lainnya</option>
            </select>
          </div>

          <input type="hidden" name="total_amount" value="<?= $grand_total ?>">
          <input type="hidden" id="saldoUser" value="<?= $saldo ?>">

          <button type="submit" class="btn btn-primary mt-2">Bayar Sekarang</button>
          <div id="saldoWarning" class="alert alert-danger mt-3 d-none">
            Saldo Anda tidak mencukupi untuk menyelesaikan pembayaran ini.
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-5">
      <div class="card p-4 mb-4">
        <h5 class="mb-3">Ringkasan Pesanan</h5>
        <ul class="list-group mb-3">
          <?php foreach ($cart_items as $item): ?>
          <li class="list-group-item d-flex justify-content-between lh-sm">
            <div class="d-flex">
              <img src="<?= htmlspecialchars($item['image']) ?>" width="60" class="me-3 rounded" alt="<?= htmlspecialchars($item['name']) ?>">
              <div>
                <h6 class="my-0"><?= htmlspecialchars($item['name']) ?></h6>
                <small class="text-muted">
                  <?= ucfirst($item['product_type']) ?>
                  <?= $item['product_type'] === 'jersey' ? '(Ukuran: ' . htmlspecialchars($item['size']) . ')' : '' ?>
                </small>
              </div>
            </div>
            <span class="text-muted">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></span>
          </li>
          <?php endforeach; ?>
          <li class="list-group-item d-flex justify-content-between">
            <span><strong>Total</strong></span>
            <strong>Rp<?= number_format($grand_total, 0, ',', '.') ?></strong>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span><strong>Saldo Anda</strong></span>
            <strong>Rp<?= number_format($saldo, 0, ',', '.') ?></strong>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById("checkoutForm").addEventListener("submit", function(e) {
  const saldo = parseInt(document.getElementById("saldoUser").value);
  const total = <?= $grand_total ?>;
  const warning = document.getElementById("saldoWarning");

  if (saldo < total) {
    e.preventDefault();
    warning.classList.remove("d-none");
    warning.scrollIntoView({ behavior: "smooth" });
  }
});
</script>

</body>
</html>
