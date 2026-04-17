<?php
session_start();

require_once __DIR__ . '/../../configs/env.php';

$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];

// Kiểm tra số lượng tồn kho
$result = $conn->query("SELECT quantity FROM products WHERE id = " . (int)$id);
$row = $result ? $result->fetch_assoc() : null;
$stock = $row ? (int)$row['quantity'] : 0;

// Lấy số lượng hiện tại trong giỏ
$currentQty = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id]['qty'] : 0;
if ($stock <= $currentQty) {
    echo json_encode([
        "status" => "error",
        "message" => "Sản phẩm không đủ số lượng!"
    ]);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += 1;
} else {
    // Lấy ảnh sản phẩm
    $imgRow = $conn->query("SELECT image FROM products WHERE id = $id");
    $img = ($imgRow && $imgRow = $imgRow->fetch_assoc()) ? $imgRow['image'] : '';
    $_SESSION['cart'][$id] = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'qty' => 1,
        'image' => $img
    ];
}

// trả về JSON
echo json_encode([
    "status" => "success"
]);