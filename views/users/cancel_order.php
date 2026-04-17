<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$user_id = $_SESSION['user']['id'];
if ($order_id > 0) {
    // Chỉ cho phép huỷ nếu đơn thuộc user và trạng thái là CHỜ DUYỆT
    $check = $conn->query("SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id AND (status IS NULL OR status = 'CHỜ DUYỆT')");
    if ($check && $check->num_rows > 0) {
        $conn->query("UPDATE orders SET status = 'ĐÃ HUỶ' WHERE id = $order_id");
        header('Location: order_history.php?msg=cancel_success');
        exit;
    }
}
header('Location: order_history.php?msg=cancel_fail');
exit;
