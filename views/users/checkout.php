<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    header('Location: cart.php'); 
    exit;
}

$total_price = 0;
$shipping_fee = 30000; 

include __DIR__ . '/header_user.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5 mb-5">
        <div class="row">
            
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Thông tin thanh toán</h4>
                    </div>
                    <div class="card-body">
                        <form action="process_payment.php" method="POST">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                       value="<?= htmlspecialchars($_SESSION['user']['name'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone" 
                                       value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Địa chỉ giao hàng</label>
                                <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" required placeholder="Số nhà, tên đường, xã/phường..."></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="order_note" class="form-label">Ghi chú đơn hàng (tuỳ chọn)</label>
                                <textarea class="form-control" id="order_note" name="order_note" rows="2" placeholder="Ghi chú về thời gian giao hàng hoặc chỉ dẫn đường đi..."></textarea>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">Xác nhận Đặt hàng</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Đơn hàng của bạn</h4>
                        <span class="badge bg-light text-dark rounded-pill"><?= count($cart) ?> sản phẩm</span>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            
                            <?php foreach ($cart as $item): ?>
                                <?php 
                                    $qty = isset($item['quantity']) ? $item['quantity'] : (isset($item['qty']) ? $item['qty'] : 1);
                                    $price = isset($item['price']) ? $item['price'] : 0;
                                    $item_total = $price * $qty;
                                    $total_price += $item_total;
                                ?>
                                <li class="list-group-item d-flex justify-content-between lh-sm py-3">
                                    <div>
                                        <h6 class="my-0"><?= htmlspecialchars($item['name']) ?></h6>
                                        <small class="text-muted">Đơn giá: <?= number_format($price, 0, ',', '.') ?> đ</small><br>
                                        <small class="text-muted">Số lượng: <?= $qty ?></small>
                                    </div>
                                    <span class="text-muted fw-bold"><?= number_format($item_total, 0, ',', '.') ?> đ</span>
                                </li>
                            <?php endforeach; ?>
                            
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <div class="text-success">
                                    <h6 class="my-0">Phí vận chuyển</h6>
                                    <small>Giao hàng tiêu chuẩn</small>
                                </div>
                                <span class="text-success">+ <?= number_format($shipping_fee, 0, ',', '.') ?> đ</span>
                            </li>
                            
                            <li class="list-group-item d-flex justify-content-between py-3">
                                <span><strong>Tổng tiền thanh toán</strong></span>
                                <strong class="text-danger fs-5"><?= number_format($total_price + $shipping_fee, 0, ',', '.') ?> đ</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="cart.php" class="text-decoration-none text-muted small">← Quay lại giỏ hàng để thay đổi</a>
                </div>
            </div>

        </div>
    </div>
</body>
</html>