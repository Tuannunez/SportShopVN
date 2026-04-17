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
    // Chỉ cho phép xoá nếu đơn thuộc user và đã huỷ
    $check = $conn->query("SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id AND status = 'ĐÃ HUỶ'");
    if ($check && $check->num_rows > 0) {
        $conn->query("DELETE FROM order_details WHERE order_id = $order_id");
        $conn->query("DELETE FROM orders WHERE id = $order_id");
        header('Location: order_history.php?msg=deleted');
        exit;
    }
}
header('Location: order_history.php?msg=fail');
exit;
