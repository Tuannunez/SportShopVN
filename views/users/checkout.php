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