<?php
// Xử lý thêm sản phẩm vào giỏ hàng khi nhấn "Mua ngay"
session_start();
require_once __DIR__ . '/../../configs/env.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

if ($id > 0) {
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    $product = $result->fetch_assoc();
    if ($product) {
        // Thêm vào giỏ hàng
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }
    }
}
// Chuyển hướng sang trang checkout
header('Location: checkout.php');
exit;
