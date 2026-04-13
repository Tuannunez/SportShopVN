<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
if ($id > 0) {
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    $product = $result->fetch_assoc();
}
if (!$product) {
    echo '<div style="color:red;text-align:center;margin:40px;">Không tìm thấy sản phẩm!</div>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">
    <style>
        .product-detail-container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px 0 rgba(0,0,0,0.07); display: flex; gap: 36px; padding: 32px; }
        .product-detail-img { flex: 0 0 320px; }
        .product-detail-img img { width: 100%; border-radius: 10px; box-shadow: 0 2px 8px 0 rgba(0,0,0,0.08); border: 1px solid #eee; }
        .product-detail-info { flex: 1; }
        .product-detail-info h2 { margin-top: 0; color: #1976d2; }
        .product-detail-price { font-size: 22px; color: #e53935; font-weight: bold; margin: 18px 0; }
        .product-detail-btns { margin-top: 28px; }
        .btn-buy { background: #1976d2; color: #fff; border: none; border-radius: 6px; padding: 12px 32px; font-size: 17px; font-weight: 500; cursor: pointer; transition: background 0.2s; }
        .btn-buy:hover { background: #125ea7; }
        .btn-back { background: #90a4ae; color: #fff; border: none; border-radius: 6px; padding: 10px 22px; font-size: 15px; margin-left: 12px; text-decoration: none; }
        .btn-back:hover { background: #78909c; }
    </style>
</head>
<body>
    <div class="product-detail-container">
        <div class="product-detail-img">
            <img src="<?= BASE_ASSETS_UPLOADS . $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </div>
        <div class="product-detail-info">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
            <div class="product-detail-price">Giá: <?= number_format($product['price']) ?>đ</div>
            <div class="product-detail-btns">
                <button class="btn-buy">Mua ngay</button>
                <a href="index.php" class="btn-back">Quay lại</a>
            </div>
        </div>
    </div>
</body>
</html>
