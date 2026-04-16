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
    <a href="index.php" class="btn btn-primary mb-3">
        ← Trở về trang chủ
    </a>

    <h2 class="mb-4">Lịch sử đơn hàng của bạn</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Mã ĐH & Sản phẩm</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($order = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            #<?= htmlspecialchars($order['id']) ?>
                            <?php
                            // Lấy tất cả sản phẩm trong đơn
                            $order_id = (int)$order['id'];
                            $details = $conn->query("SELECT od.*, p.name, p.image FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = $order_id");
                            if ($details && $details->num_rows > 0): ?>
                                <div style="margin-top:6px;">
                                    <b style="font-size:13px;">Sản phẩm:</b>
                                    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:4px;">
                                        <?php while ($d = $details->fetch_assoc()): ?>
                                            <div style="display:flex;align-items:center;gap:6px;background:#f8f9fa;padding:4px 8px;border-radius:6px;border:1px solid #eee;min-width:0;max-width:180px;">
                                                <img 
                                                    src="<?= BASE_ASSETS_UPLOADS . $d['image'] ?>" 
                                                    alt="<?= htmlspecialchars($d['name']) ?>"
                                                    style="width:32px;height:32px;object-fit:cover;border-radius:5px;border:1px solid #e0e0e0;"
                                                >
                                                <span style="font-size:12px;max-width:110px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                    <?= htmlspecialchars($d['name']) ?> x <?= $d['quantity'] ?>
                                                </span>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?= isset($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '' ?>
                        </td>

                        <td class="text-danger fw-bold">
                            <?= number_format($order['total_amount'], 0, ',', '.') ?>đ
                        </td>

                        <td>
                            <?php
                            $st = strtoupper($order['status'] ?? 'CHỜ DUYỆT');

                            if ($st === 'ĐÃ HUỶ') {
                                echo '<span class="badge bg-danger">ĐÃ HUỶ</span>';
                            } elseif ($st === 'ĐÃ GIAO') {
                                echo '<span class="badge bg-success">ĐÃ GIAO</span>';
                            } elseif ($st === 'CHỜ DUYỆT') {
                                echo '<span class="badge bg-warning text-dark">CHỜ DUYỆT</span>';
                            } else {
                                echo '<span class="badge bg-secondary">' . $st . '</span>';
                            }
                            ?>
                        </td>

                        <td>
                            <a href="order_detail.php?order_id=<?= $order['id'] ?>" 
                               class="btn btn-sm btn-info">
                                Xem chi tiết
                            </a>

                            <?php if ($st === 'CHỜ DUYỆT'): ?>
                                <a href="cancel_order.php?order_id=<?= $order['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn chắc chắn muốn huỷ đơn này?')">
                                    Huỷ đơn
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Chưa có đơn hàng nào</td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>

</body>
</html>