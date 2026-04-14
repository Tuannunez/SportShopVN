<?php
require_once __DIR__ . '/../configs/env.php';
require_once __DIR__ . '/../configs/helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $user_id = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : 0;
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    if ($product_id > 0 && $user_id > 0 && $rating > 0 && $comment !== '') {
        $stmt = $conn->prepare("INSERT INTO product_reviews (product_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param('iiis', $product_id, $user_id, $rating, $comment);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Đã gửi đánh giá!']);
            exit;
        }
    }
    echo json_encode(['success' => false, 'message' => 'Gửi đánh giá thất bại!']);
    exit;
}
echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ!']);
exit;
