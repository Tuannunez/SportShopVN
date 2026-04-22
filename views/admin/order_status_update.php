<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    echo json_encode(['success'=>false, 'message'=>'Bạn không có quyền thực hiện!']);
    exit;
}

$order_id = $_POST['order_id'] ?? 0;
$action = $_POST['action'] ?? '';

if (!$order_id || !$action) {
    echo json_encode(['success'=>false, 'message'=>'Thiếu thông tin!']);
    exit;
}

$status = '';
$is_paid = null;
switch ($action) {
    case 'approve':
        $status = 'ĐANG GIAO';
        break;
    case 'done':
        $status = 'ĐÃ GIAO';
        $is_paid = 1;
        break;
    case 'cancel':
        $status = 'ĐÃ HUỶ';
        $is_paid = 0;
        break;
    default:
        echo json_encode(['success'=>false, 'message'=>'Hành động không hợp lệ!']);
        exit;
}

if ($is_paid !== null) {
    $stmt = $conn->prepare("UPDATE orders SET status=?, is_paid=? WHERE id=?");
    $stmt->bind_param('sii', $status, $is_paid, $order_id);
} else {
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param('si', $status, $order_id);
}
if ($stmt->execute()) {
    echo json_encode(['success'=>true, 'message'=>'Cập nhật trạng thái thành công!', 'status'=>$status]);
} else {
    echo json_encode(['success'=>false, 'message'=>'Cập nhật thất bại!']);
}
