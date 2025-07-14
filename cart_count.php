<?php
session_start();
require_once 'koneksi.php';

$session_id = session_id();

$query = "SELECT SUM(quantity) as total_items FROM cart WHERE session_id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $session_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo $data['total_items'] ?? 0;
