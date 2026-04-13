<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>



<div class="sidebar">
    <h2>SportShopVN</h2>
    <a href="#">Dashboard</a>
    <a href="product_san_pham.php">Sản phẩm</a>
    <a href="#">Danh mục</a>
    <a href="#">Đơn hàng</a>
    <a href="#">User</a>
    <a href="logout.php">Đăng xuất</a>
</div>


<div class="main">
    <h1>Dashboard</h1>
    <div style="margin-bottom: 16px; color: #333; font-size: 18px;">
        Xin chào, <b><?= htmlspecialchars($_SESSION['admin']['name']) ?></b>!
    </div>

    <div class="cards">
        <div class="card">
            <h3>120</h3>
            <p>Đơn hàng</p>
        </div>

        <div class="card">
            <h3>50</h3>
            <p>Sản phẩm</p>
        </div>

        <div class="card">
            <h3>30</h3>
            <p>User</p>
        </div>
    </div>

    <a class="logout" href="logout.php">Đăng xuất</a>
</div>

</body>
</html>