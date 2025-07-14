<?php
session_start();
require_once 'koneksi.php';

if (isset($_GET['cart_id'])) {
    $cart_id = intval($_GET['cart_id']);
    $stmt = $koneksi->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
} elseif (isset($_GET['clear_all']) && $_GET['clear_all'] == 'true') {
    $session_id = session_id();
    $stmt = $koneksi->prepare("DELETE FROM cart WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
}

header("Location: cart.php");
exit;
