<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($order_id > 0) {
    // Xoá chi tiết đơn hàng trước
    $conn->query("DELETE FROM order_details WHERE order_id = $order_id");
    // Xoá đơn hàng
    $conn->query("DELETE FROM orders WHERE id = $order_id");
    header('Location: admin_orders.php?msg=deleted');
    exit;
}
header('Location: admin_orders.php?msg=fail');
exit;
