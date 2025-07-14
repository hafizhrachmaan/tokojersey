<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? null;
    $product_type = $_POST['product_type'] ?? null;
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
    $size = $_POST['size'] ?? '';
    $session_id = session_id();

    if (!$product_id || !$product_type || $quantity < 1 || ($product_type === 'jersey' && $size === '')) {
        header("Location: cart.php?error=invalid_input");
        exit;
    }

    if ($product_type !== 'jersey') {
        $size = '';
    }

    $checkQuery = "SELECT id FROM cart WHERE session_id = ? AND product_id = ? AND product_type = ? AND size = ?";
    $stmt = $koneksi->prepare($checkQuery);
    $stmt->bind_param("ssss", $session_id, $product_id, $product_type, $size);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $updateQuery = "UPDATE cart SET quantity = quantity + ? WHERE session_id = ? AND product_id = ? AND product_type = ? AND size = ?";
        $stmtUpdate = $koneksi->prepare($updateQuery);
        $stmtUpdate->bind_param("issss", $quantity, $session_id, $product_id, $product_type, $size);
        $stmtUpdate->execute();
    } else {
        $insertQuery = "INSERT INTO cart (session_id, product_id, product_type, size, quantity) VALUES (?, ?, ?, ?, ?)";
        $stmtInsert = $koneksi->prepare($insertQuery);
        $stmtInsert->bind_param("ssssi", $session_id, $product_id, $product_type, $size, $quantity);
        $stmtInsert->execute();
    }

    header("Location: cart.php");
    exit;
} else {
    header("Location: jersey.php");
    exit;
}
