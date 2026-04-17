<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $order_id = (int)($_POST['order_id'] ?? 0);
    $user_id = (int)($_POST['user_id'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    $created_at = date('Y-m-d H:i:s');

    if ($product_id && $order_id && $user_id && $rating && $comment) {
        $stmt = $conn->prepare("INSERT INTO product_reviews (product_id, user_id, rating, comment, order_id, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('iiisis', $product_id, $user_id, $rating, $comment, $order_id, $created_at);
        if ($stmt->execute()) {
            header('Location: order_detail.php?order_id=' . $order_id . '&review=success');
            exit;
        } else {
            echo '<div style="color:red;text-align:center;margin:40px;">Lỗi khi lưu đánh giá!</div>';
        }
    } else {
        echo '<div style="color:red;text-align:center;margin:40px;">Vui lòng nhập đầy đủ thông tin đánh giá!</div>';
    }
} else {
    header('Location: order_history.php');
    exit;
}
