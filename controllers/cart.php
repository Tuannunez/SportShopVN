<?php
require_once __DIR__ . '/../configs/env.php';
require_once __DIR__ . '/../configs/helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    if ($product_id > 0 && $quantity > 0) {
        $result = $conn->query("SELECT * FROM products WHERE id = $product_id");
        $product = $result->fetch_assoc();
        if ($product) {
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => $quantity
                ];
            }
            echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng!']);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'Thêm giỏ hàng thất bại!']);
    exit;
}
echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ!']);
exit;
