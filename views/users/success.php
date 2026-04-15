<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

// Lấy thông tin đơn hàng từ session hoặc GET/POST (tùy luồng xử lý của bạn)
$order_id = $_GET['order_id'] ?? null;
$order = null;
$order_items = [];
if ($order_id) {
    // Lấy thông tin đơn hàng
    $sql = "SELECT * FROM orders WHERE id = $order_id";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $order = $result->fetch_assoc();
    }
    // Lấy danh sách sản phẩm trong đơn (nếu có bảng order_items)
    $sql_items = "SELECT * FROM order_items WHERE order_id = $order_id";
    $result_items = $conn->query($sql_items);
    if ($result_items && $result_items->num_rows > 0) {
        while ($row = $result_items->fetch_assoc()) {
            $order_items[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include __DIR__ . '/header_user.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-success text-center">
                    <h3>Đặt hàng thành công!</h3>
                    <p>Cảm ơn bạn đã mua hàng tại SportShopVN.</p>
                </div>
                <?php if ($order): ?>
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thông tin đơn hàng #<?= htmlspecialchars($order['id']) ?></h5>
                    </div>
                    <div class="card-body">
                        <p><b>Người nhận:</b> <?= htmlspecialchars($order['customer_name']) ?></p>
                        <p><b>Số điện thoại:</b> <?= htmlspecialchars($order['customer_phone']) ?></p>
                        <p><b>Địa chỉ:</b> <?= htmlspecialchars($order['shipping_address']) ?></p>
                        <p><b>Ghi chú:</b> <?= htmlspecialchars($order['order_note'] ?? '') ?></p>
                        <p><b>Phương thức thanh toán:</b> <?= htmlspecialchars($order['payment_method'] ?? 'COD') ?></p>
                        <p><b>Tổng tiền:</b> <span class="text-danger fw-bold"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</span></p>
                    </div>
                </div>
                <?php if (!empty($order_items)): ?>
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Danh sách sản phẩm</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead><tr><th>Tên sản phẩm</th><th>Số lượng</th><th>Giá</th></tr></thead>
                            <tbody>
                            <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                <div class="text-center">
                    <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
                    <a href="order_history.php" class="btn btn-outline-secondary">Xem lịch sử đơn hàng</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
