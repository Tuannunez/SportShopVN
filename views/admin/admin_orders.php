<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';
// Kiểm tra quyền admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
// Lấy danh sách đơn hàng
$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Đơn hàng - Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <h2>SportShopVN</h2>
    <a href="dashboard.php" class="active">Dashboard</a>
    <a href="product_san_pham.php">Sản phẩm</a>
    <a href="#">Danh mục</a>
    <a href="admin_orders.php">Đơn hàng</a>
    <a href="admin_users.php">Tài khoản</a>
    <a href="logout.php">Đăng xuất</a>
</div>

<div class="main">
    <h1>Danh sách Đơn hàng mới</h1>
    <div class="table-responsive" style="margin-top:32px;">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Địa chỉ</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?= htmlspecialchars($order['id']) ?></td>
                    <td><?= htmlspecialchars($order['customer_name']) ?></td>
                    <td><?= htmlspecialchars($order['customer_phone']) ?></td>
                    <td><?= htmlspecialchars($order['shipping_address']) ?></td>
                    <td class="text-danger fw-bold"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</td>
                    <td>
                        <?php
                        $st = strtoupper($order['status'] ?? 'CHỜ DUYỆT');
                        $badge = 'bg-secondary';
                        $txt = '';
                        if ($st === 'CHỜ DUYỆT') { $badge = 'bg-warning text-dark'; $txt = 'Chờ duyệt'; }
                        elseif ($st === 'ĐANG XỬ LÍ') { $badge = 'bg-warning text-dark'; $txt = 'Đang xử lí'; }
                        elseif ($st === 'ĐANG GIAO') { $badge = 'bg-info text-white'; $txt = 'Đang giao'; }
                        elseif ($st === 'ĐÃ GIAO') { $badge = 'bg-success text-white'; $txt = 'Đã giao'; }
                        elseif ($st === 'ĐÃ HUỶ') { $badge = 'bg-danger text-white'; $txt = 'Khách đã huỷ'; }
                        else { $txt = $st; }
                        ?>
                        <span class="badge <?= $badge ?>" style="font-size:15px;min-width:90px;display:inline-block;">
                            <?= $txt ?>
                        </span>
                    </td>
                    <td><?= isset($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '' ?></td>
                    <td>
                        <?php if ($st === 'CHỜ DUYỆT'): ?>
                            <button class="btn btn-sm btn-primary" onclick="updateStatus(<?= $order['id'] ?>, 'approve', this)">Duyệt đơn</button>
                        <?php elseif ($st === 'ĐANG XỬ LÍ'): ?>
                            <button class="btn btn-sm btn-info" onclick="updateStatus(<?= $order['id'] ?>, 'approve', this)">Chuyển Đang giao</button>
                        <?php elseif ($st === 'ĐANG GIAO'): ?>
                            <button class="btn btn-sm btn-success" onclick="updateStatus(<?= $order['id'] ?>, 'done', this)">Chuyển Đã giao</button>
                        <?php else: ?>
                            <span class="text-success">Hoàn tất</span>
                        <?php endif; ?>
                        <?php if ($st === 'ĐÃ HUỶ'): ?>
                            <a href="delete_order.php?order_id=<?= $order['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xoá đơn này?')">Xoá đơn</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <!-- Hiển thị danh sách sản phẩm trong đơn hàng -->
                <tr>
                    <td colspan="8" style="padding:0;background:#fafbfc;">
                        <div style="padding:12px 18px 8px 18px;">
                        <b>Sản phẩm:</b>
                        <table style="width:100%;margin-top:8px;border:1px solid #e0e0e0;background:#fff;border-radius:8px;overflow:hidden;">
                            <thead>
                                <tr style="background:#f5f5f5;">
                                    <th style="padding:8px 4px;text-align:center;border-bottom:1px solid #e0e0e0;">Ảnh</th>
                                    <th style="padding:8px 4px;text-align:left;border-bottom:1px solid #e0e0e0;">Tên sản phẩm</th>
                                    <th style="padding:8px 4px;text-align:center;border-bottom:1px solid #e0e0e0;">Số lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $order_id = (int)$order['id'];
                            $details = $conn->query("SELECT * FROM order_details WHERE order_id = $order_id");
                            if ($details && $details->num_rows > 0):
                                while ($d = $details->fetch_assoc()): ?>
                                    <tr>
                                        <td style="text-align:center;padding:8px 4px;border-bottom:1px solid #f0f0f0;"><img src="<?= BASE_ASSETS_UPLOADS . $d['product_image'] ?>" alt="<?= htmlspecialchars($d['product_name']) ?>" style="width:54px;height:54px;object-fit:cover;border-radius:7px;border:1px solid #e0e0e0;"></td>
                                        <td style="padding:8px 4px;border-bottom:1px solid #f0f0f0;vertical-align:middle;"> <?= htmlspecialchars($d['product_name']) ?> </td>
                                        <td style="text-align:center;padding:8px 4px;border-bottom:1px solid #f0f0f0;vertical-align:middle;"> <?= $d['quantity'] ?> </td>
                                    </tr>
                                <?php endwhile; else: ?>
                                    <tr><td colspan="3" style="text-align:center;padding:12px 0;">Không có sản phẩm nào.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        </div>
                    </td>
                </tr>
                <?php endwhile; else: ?>
                <tr><td colspan="8" style="text-align:center;">Chưa có đơn hàng nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function updateStatus(orderId, action, btn) {
    btn.disabled = true;
    fetch('order_status_update.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'order_id=' + orderId + '&action=' + action
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
            btn.disabled = false;
        }
    })
    .catch(() => { alert('Lỗi kết nối!'); btn.disabled = false; });
}
</script>
</body>
</html>