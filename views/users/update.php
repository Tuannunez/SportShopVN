<?php
session_start();

require_once __DIR__ . '/../../configs/env.php';

$id = $_POST['id'];
$action = $_POST['action'];

if (isset($_SESSION['cart'][$id])) {

    if ($action == 'increase') {
        // Kiểm tra tồn kho
        $result = $conn->query("SELECT quantity FROM products WHERE id = " . (int)$id);
        $row = $result ? $result->fetch_assoc() : null;
        $stock = $row ? (int)$row['quantity'] : 0;
        if ($_SESSION['cart'][$id]['qty'] < $stock) {
            $_SESSION['cart'][$id]['qty']++;
        }
    }

    if ($action == 'decrease') {
        if ($_SESSION['cart'][$id]['qty'] > 1) {
            $_SESSION['cart'][$id]['qty']--;
        }
        // Nếu số lượng là 1 thì không giảm nữa, không xóa sản phẩm
    }

    if ($action == 'remove') {
        unset($_SESSION['cart'][$id]);
    }
}

echo json_encode(["status" => "ok"]);