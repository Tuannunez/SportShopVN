<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
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
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Thông tin thanh toán</h4>
                    </div>
                    <div class="card-body">
                        <form action="process_payment.php" method="POST">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Địa chỉ giao hàng</label>
                                <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="order_note" class="form-label">Ghi chú đơn hàng (tuỳ chọn)</label>
                                <textarea class="form-control" id="order_note" name="order_note" rows="2" placeholder="Ghi chú cho shop..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phương thức thanh toán</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                    <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="bank" value="bank">
                                    <label class="form-check-label" for="bank">Chuyển khoản ngân hàng</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="e_wallet" value="e_wallet">
                                    <label class="form-check-label" for="e_wallet">Ví điện tử (ZaloPay, Momo...)</label>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">Xác nhận Đặt hàng</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>