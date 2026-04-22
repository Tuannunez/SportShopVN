<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($order_id <= 0) {
    echo '<div style="padding:32px;">Không tìm thấy đơn hàng.</div>';
    exit;
}
// Lấy thông tin đơn hàng
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();
if (!$order) {
    echo '<div style="padding:32px;">Không tìm thấy đơn hàng.</div>';
    exit;
}
// Lấy chi tiết sản phẩm
$details = $conn->query("SELECT * FROM order_details WHERE order_id = $order_id");
// Lấy lịch sử trạng thái
$history = $conn->query("SELECT * FROM order_status_history WHERE order_id = $order_id ORDER BY changed_at DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Đơn hàng #<?= htmlspecialchars($order['order_code'] ?? $order['id']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-detail-box { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px #eee; padding: 24px; margin-bottom: 24px; }
        .order-detail-title { font-size: 22px; font-weight: bold; margin-bottom: 18px; }
        .order-detail-label { font-weight: 500; }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>SportShopVN</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="product_san_pham.php">Sản phẩm</a>
    <a href="category_list.php">Danh mục</a>
    <a href="admin_orders.php" class="active">Đơn hàng</a>
    <a href="admin_users.php">Tài khoản</a>
    <a href="logout.php">Đăng xuất</a>
</div>
<div class="main">
    <h1>Chi Tiết Đơn Hàng #<?= htmlspecialchars($order['order_code'] ?? $order['id']) ?></h1>
    <div class="order-detail-box">
        <div class="order-detail-title">#1. Thông Tin Đơn Hàng</div>
        <div class="row mb-2">
            <div class="col-md-3 mb-2">
                <label class="order-detail-label">Mã Đơn Hàng</label>
                <input class="form-control" value="<?= htmlspecialchars($order['order_code'] ?? $order['id']) ?>" readonly>
            </div>
            <div class="col-md-3 mb-2">
                <label class="order-detail-label">Phương Thức Thanh Toán</label>
                <input class="form-control" value="<?= htmlspecialchars($order['payment_method'] ?? 'Chưa rõ') ?>" readonly>
            </div>
            <div class="col-md-3 mb-2">
                <label class="order-detail-label">Trạng Thái Thanh Toán</label>
                <input class="form-control" value="<?= ($order['is_paid'] ?? 0) ? 'Đã Thanh Toán' : 'Chưa Thanh Toán' ?>" readonly>
            </div>
            <div class="col-md-3 mb-2">
                <label class="order-detail-label">Trạng Thái Đơn Hàng</label>
                <input class="form-control" value="<?= htmlspecialchars($order['status']) ?>" readonly>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4 mb-2">
                <label class="order-detail-label">Tên Khách Hàng</label>
                <input class="form-control" value="<?= htmlspecialchars($order['customer_name']) ?>" readonly>
            </div>
            <div class="col-md-4 mb-2">
                <label class="order-detail-label">Điện Thoại</label>
                <input class="form-control" value="<?= htmlspecialchars($order['customer_phone']) ?>" readonly>
            </div>
            <div class="col-md-4 mb-2">
                <label class="order-detail-label">Địa Chỉ</label>
                <input class="form-control" value="<?= htmlspecialchars($order['shipping_address']) ?>" readonly>
            </div>
        </div>
    </div>
    <div class="order-detail-box">
        <div class="order-detail-title">#2. Thông Tin Sản Phẩm</div>
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Số Lượng</th>
                    <th>Giá Bán</th>
                    <th>Thành Tiền</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=1; $total=0; if ($details && $details->num_rows > 0):
                while ($d = $details->fetch_assoc()):
                    $thanh_tien = $d['quantity'] * $d['price'];
                    $total += $thanh_tien;
                    // Lấy đánh giá cho sản phẩm này trong đơn này
                    $product_id = (int)$d['product_id'];
                    $reviews = [];
                    $rv = $conn->query("SELECT r.*, u.name as user_name FROM product_reviews r JOIN users u ON r.user_id=u.id WHERE r.product_id = $product_id AND r.order_id = $order_id");
                    if ($rv && $rv->num_rows > 0) {
                        while ($row = $rv->fetch_assoc()) $reviews[] = $row;
                    }
            ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($d['product_name']) ?></td>
                    <td><?= $d['quantity'] ?></td>
                    <td><?= number_format($d['price'], 0, ',', '.') ?>đ</td>
                    <td><?= number_format($thanh_tien, 0, ',', '.') ?>đ</td>
                </tr>
                <?php if (!empty($reviews)): ?>
                <tr>
                    <td></td>
                    <td colspan="4">
                        <div style="background:#f8f9fa;padding:12px 18px;border-radius:7px;margin-bottom:8px;">
                            <b>Đánh giá/Bình luận của khách:</b><br>
                            <?php foreach($reviews as $review): ?>
                                <div style="margin-bottom:8px;padding-bottom:8px;border-bottom:1px solid #eee;">
                                    <span style="color:#ff9800;">
                                        <?php for($s=1;$s<=5;$s++): ?>
                                            <?= $s <= $review['rating'] ? '★' : '☆' ?>
                                        <?php endfor; ?>
                                    </span>
                                    <span style="font-weight:500;"> <?= htmlspecialchars($review['user_name']) ?> </span>:
                                    <span><?= htmlspecialchars($review['comment']) ?></span>
                                    <span style="color:#888;font-size:13px;">(<?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>)</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endwhile; else: ?>
                <tr><td colspan="5" class="text-center">Không có sản phẩm nào.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <div>
                <?php $voucher = $order['voucher_discount'] ?? 0; ?>
                <div>Mã giảm giá (Voucher): <span class="text-danger">-<?= number_format($voucher, 0, ',', '.') ?>đ</span></div>
                <div class="fw-bold">Tổng: <?= number_format($total - $voucher, 0, ',', '.') ?>đ</div>
            </div>
        </div>
    </div>
    <div class="order-detail-box">
        <div class="order-detail-title">#3. Lịch Sử Thay Đổi Trạng Thái</div>
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>STT</th>
                    <th>Trạng Thái Thay Đổi</th>
                    <th>Ghi Chú</th>
                    <th>Người Thay Đổi</th>
                    <th>Thời Gian</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=1; if ($history && $history->num_rows > 0):
                while ($h = $history->fetch_assoc()): ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= htmlspecialchars($h['status_change']) ?></td>
                    <td><?= htmlspecialchars($h['note']) ?></td>
                    <td><?= htmlspecialchars($h['changed_by']) ?></td>
                    <td><?= date('H:i:s d/m/Y', strtotime($h['changed_at'])) ?></td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="5" class="text-center">Chưa có lịch sử thay đổi.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="order-detail-box">
        <div class="order-detail-title">#4. Thay Đổi Trạng Thái Đơn Hàng</div>
        <form id="order-status-form" class="row g-3">
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <div class="col-md-6">
                <label class="order-detail-label">Trạng Thái</label>
                <select name="action" class="form-select" required>
                    <option value="approve">Đang Giao Hàng</option>
                    <option value="done">Đã Giao</option>
                    <option value="cancel">Đã Huỷ</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="order-detail-label">Ghi chú</label>
                <input type="text" name="note" class="form-control" placeholder="Ghi chú (nếu có)">
            </div>
            <div class="col-12 d-flex justify-content-end">
                <a href="admin_orders.php" class="btn btn-secondary me-2">Huỷ</a>
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </form>
        <script>
        document.getElementById('order-status-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = this;
            var formData = new FormData(form);
            var btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            fetch('order_status_update.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Đã lưu thành công!');
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
                btn.disabled = false;
            })
            .catch(() => {
                alert('Lỗi kết nối!');
                btn.disabled = false;
            });
        });
        </script>
    </div>
</div>
</body>
</html>
