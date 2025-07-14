<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = isset($_POST['cart_id']) ? intval($_POST['cart_id']) : 0;
    $quantity = max(1, intval($_POST['quantity']));
    $session_id = session_id();

    if ($cart_id > 0) {
        $stmt = $koneksi->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND session_id = ?");
        $stmt->bind_param("iis", $quantity, $cart_id, $session_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: cart.php");
    exit;
}
?>
