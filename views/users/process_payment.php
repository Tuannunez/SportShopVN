<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
    $user_id = $_SESSION['user']['id'];
    $name = $_POST['customer_name'] ?? '';
    $phone = $_POST['customer_phone'] ?? '';
    $address = $_POST['shipping_address'] ?? '';
    $order_note = $_POST['order_note'] ?? '';
    $payment_method = $_POST['payment_method'] ?? 'cod';
    // Tính tổng tiền thực tế từ giỏ hàng
    $totalAmount = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $qty = isset($item['qty']) ? $item['qty'] : (isset($item['quantity']) ? $item['quantity'] : 1);
            $totalAmount += $item['price'] * $qty;
        }
    }

    // Lưu đơn hàng
    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_phone, shipping_address, order_note, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('isssssi', $user_id, $name, $phone, $address, $order_note, $payment_method, $totalAmount);
    if ($stmt->execute()) {
        $order_id = $conn->insert_id;
        // Lưu chi tiết sản phẩm vào bảng order_details
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id => $item) {
                $quantity = isset($item['qty']) ? $item['qty'] : (isset($item['quantity']) ? $item['quantity'] : 1);
                $price = $item['price'];
                // Lấy tên và ảnh sản phẩm tại thời điểm đặt hàng
                $productInfo = $conn->query("SELECT name, image, quantity FROM products WHERE id = " . (int)$product_id);
                $row = $productInfo ? $productInfo->fetch_assoc() : null;
                $stock = $row ? (int)$row['quantity'] : 0;
                $product_name = $row ? $row['name'] : '';
                $product_image = $row ? $row['image'] : '';
                if ($quantity > $stock) {
                    echo "<div style='color:red;text-align:center;margin:40px;'>Sản phẩm ID $product_id không đủ số lượng! Đặt hàng thất bại.</div>";
                    exit;
                }
                // Trừ số lượng sản phẩm
                $conn->query("UPDATE products SET quantity = quantity - $quantity WHERE id = $product_id");
                // Lưu cả tên và ảnh vào order_details (cần có cột product_name, product_image)
                $stmtDetail = $conn->prepare("INSERT INTO order_details (order_id, product_id, product_name, product_image, quantity, price) VALUES (?, ?, ?, ?, ?, ?)");
                $stmtDetail->bind_param('iissid', $order_id, $product_id, $product_name, $product_image, $quantity, $price);
                $stmtDetail->execute();
            }
        }
        // Xoá giỏ hàng sau khi đặt hàng thành công
        unset($_SESSION['cart']);
        header("Location: success.php?order_id=" . $order_id);
        exit;
    } else {
        echo "<div style='color:red;text-align:center;margin:40px;'>Có lỗi xảy ra khi đặt hàng!</div>";
    }
} else {
    header('Location: checkout.php');
    exit;
}
