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
    // Giả sử tổng tiền lấy từ session/cart, ở đây demo tạm 500000
    $totalAmount = 500000;

    // Lưu đơn hàng
    $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_phone, shipping_address, order_note, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('isssssi', $user_id, $name, $phone, $address, $order_note, $payment_method, $totalAmount);
    if ($stmt->execute()) {
        $order_id = $conn->insert_id;
        // TODO: Lưu chi tiết sản phẩm vào bảng order_items nếu có
        header("Location: success.php?order_id=" . $order_id);
        exit;
    } else {
        echo "<div style='color:red;text-align:center;margin:40px;'>Có lỗi xảy ra khi đặt hàng!</div>";
    }
} else {
    header('Location: checkout.php');
    exit;
}
