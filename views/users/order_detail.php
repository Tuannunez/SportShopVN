<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($order_id <= 0) {
    echo '<div style="color:red;text-align:center;margin:40px;">Thiếu mã đơn hàng.</div>';
    exit;
}
// Lấy thông tin đơn hàng
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();
if (!$order) {
    echo '<div style="color:red;text-align:center;margin:40px;">Không tìm thấy đơn hàng!</div>';
    exit;
}
// Lấy chi tiết sản phẩm trong đơn hàng
$details = $conn->query("SELECT od.*, p.name, p.image FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = $order_id");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?= $order_id ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">
</head>
<body>
<div style="max-width:900px;margin:30px auto;background:#fff;padding:32px 28px 24px 28px;border-radius:12px;box-shadow:0 2px 12px #e3eafc;">
    <a href="order_history.php" style="display:inline-block;margin-bottom:18px;background:#1976d2;color:#fff;padding:8px 18px;border-radius:7px;text-decoration:none;">← Quay lại lịch sử</a>
    <h2 style="margin-bottom:18px;">Chi tiết đơn hàng #<?= $order_id ?></h2>
    <div><b>Ngày đặt:</b> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></div>
    <div><b>Trạng thái:</b> <?= htmlspecialchars($order['status'] ?? '') ?></div>
    <div><b>Tổng tiền:</b> <span style="color:#e53935;font-weight:600;"><?= number_format($order['total_amount']) ?>đ</span></div>
    <div style="margin:18px 0 10px 0;font-weight:600;">Danh sách sản phẩm:</div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="background:#f5f5f5;">
                <th style="padding:8px 6px;">Ảnh</th>
                <th style="padding:8px 6px;">Tên sản phẩm</th>
                <th style="padding:8px 6px;">Số lượng</th>
                <th style="padding:8px 6px;">Đơn giá</th>
                <th style="padding:8px 6px;">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($details && $details->num_rows > 0):
            // Reset lại con trỏ để duyệt 2 lần
            $details->data_seek(0);
            while ($d = $details->fetch_assoc()): ?>
            <tr>
                <td style="text-align:center;padding:8px 6px;"><img src="<?= BASE_ASSETS_UPLOADS . ($d['image'] ?? '') ?>" alt="<?= htmlspecialchars($d['name'] ?? '') ?>" style="width:60px;height:60px;object-fit:cover;border-radius:7px;"></td>
                <td style="padding:8px 6px;"> <?= htmlspecialchars($d['name'] ?? '') ?> </td>
                <td style="text-align:center;"> <?= $d['quantity'] ?> </td>
                <td style="text-align:right;"> <?= number_format($d['price']) ?>đ </td>
                <td style="text-align:right;"> <?= number_format($d['price'] * $d['quantity']) ?>đ </td>
            </tr>
            <?php if (strtoupper($order['status'] ?? '') === 'ĐÃ GIAO' && isset($_SESSION['user'])): ?>
            <tr>
                <td colspan="5" style="background:#f8f9fa;padding:18px 18px 10px 18px;">
                    <b>Bình luận & đánh giá sản phẩm:</b>
                    <form method="post" action="product_review_submit.php" style="margin-top:8px;display:flex;flex-wrap:wrap;gap:10px;align-items:center;">
                        <input type="hidden" name="product_id" value="<?= $d['product_id'] ?>">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                        <input type="hidden" name="user_id" value="<?= $_SESSION['user']['id'] ?>">
                        <select name="rating" required style="padding:4px 8px;border-radius:5px;border:1px solid #ccc;">
                            <option value="">Chọn sao</option>
                            <option value="5">5 ★</option>
                            <option value="4">4 ★</option>
                            <option value="3">3 ★</option>
                            <option value="2">2 ★</option>
                            <option value="1">1 ★</option>
                        </select>
                        <input type="text" name="comment" placeholder="Viết bình luận..." style="flex:1;min-width:180px;padding:6px 10px;border-radius:6px;border:1px solid #ccc;" required>
                        <button type="submit" class="btn btn-success btn-sm">Gửi đánh giá</button>
                    </form>
                </td>
            </tr>
            <?php endif; ?>
        <?php endwhile; else: ?>
            <tr><td colspan="5" style="text-align:center;">Không có sản phẩm nào trong đơn hàng này.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
