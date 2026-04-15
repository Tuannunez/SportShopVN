<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user']['id'];
// Lấy danh sách đơn hàng của user
$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đơn hàng</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include __DIR__ . '/header_user.php'; ?>
<div class="container mt-5">
    <a href="index.php" class="btn btn-primary" style="font-size:17px;padding:7px 22px 7px 18px;border-radius:6px;margin-bottom:18px;box-shadow:0 2px 8px 0 rgba(25,118,210,0.08);font-weight:500;">
        ← Trở về trang chủ
    </a>
    <h2 class="mb-4">Lịch sử đơn hàng của bạn</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Mã ĐH</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?= htmlspecialchars($order['id']) ?></td>
                    <td><?= isset($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '' ?></td>
                    <td class="text-danger fw-bold"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</td>
                    <td><span class="badge bg-warning text-dark"><?= strtoupper($order['status'] ?? 'CHỜ DUYỆT') ?></span></td>
                    <td>
                        <a href="order_detail.php?order_id=<?= $order['id'] ?>" class="btn btn-sm btn-info">Xem chi tiết</a>
                        <?php if (($order['status'] ?? '') == 'CHỜ DUYỆT'): ?>
                        <a href="cancel_order.php?order_id=<?= $order['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn huỷ đơn này?')">Huỷ đơn</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="5" style="text-align:center;">Bạn chưa có đơn hàng nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
