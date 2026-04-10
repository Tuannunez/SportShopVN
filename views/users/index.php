<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';
// Nếu chưa đăng nhập, không chuyển hướng mà chỉ ẩn thông tin user
$isLogin = isset($_SESSION['user']);
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">


<header class="user-header">
    <div class="container-header">
        <div class="logo">SportShopVN</div>
        <nav>
        <?php if ($isLogin): ?>
            <span class="welcome">Xin chào, <b><?= htmlspecialchars($_SESSION['user']['name']) ?></b></span>
            <a class="logout" href="logout.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">Đăng xuất</a>
        <?php else: ?>
            <a class="login-btn" href="login.php">Đăng nhập</a>
            <a class="register-btn" href="register.php">Đăng ký</a>
        <?php endif; ?>
        </nav>
    </div>
</header>

<div class="banner-user">
    <div class="banner-content">
        <h1>Chào mừng đến với SportShopVN</h1>
        <p>Khám phá hàng trăm sản phẩm thời trang thể thao mới nhất, giá tốt nhất!</p>
       
    </div>
</div>

<main class="product-main">
    <h2 class="section-title">Sản phẩm mới</h2>
    <div class="product-grid">
        <!-- Demo sản phẩm, bạn có thể thay bằng vòng lặp PHP lấy từ DB -->
        <?php for ($i = 1; $i <= 8; $i++): ?>
        <div class="product-card">
            <div class="product-img">
                <img src="https://via.placeholder.com/220x220?text=Product+<?= $i ?>" alt="Sản phẩm <?= $i ?>">
            </div>
            <div class="product-info">
                <div class="product-name">Sản phẩm <?= $i ?></div>
                <div class="product-price">Giá: <?= number_format(199000 + $i*10000) ?>đ</div>
                <button class="buy-btn">Mua ngay</button>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</main>
<style>
body {
    background: #f4f6f9;
    margin: 0;
    font-family: Arial, sans-serif;
}
.user-header {
    background: #222;
    color: #fff;
    padding: 0;
    margin-bottom: 32px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.container-header {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 32px;
}
.logo {
    font-size: 2rem;
    font-weight: bold;
    letter-spacing: 1px;
}
.user-header nav {
    display: flex;
    align-items: center;
    gap: 18px;
}
.welcome {
    font-size: 1rem;
    margin-right: 10px;
}
.logout {
    color: #fff;
    background: #e74c3c;
    padding: 7px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.2s;
}
.logout:hover {
    background: #c0392b;
}
.login-btn, .register-btn {
    color: #fff;
    background: #1e90ff;
    padding: 7px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    margin-left: 8px;
    transition: background 0.2s;
}
.register-btn {
    background: #38b6ff;
}
.login-btn:hover {
    background: #0a58ca;
}
.register-btn:hover {
    background: #0074d9;
}
.product-main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px 40px 24px;
}
.section-title {
    text-align: left;
    font-size: 1.7rem;
    font-weight: 700;
    margin-bottom: 28px;
    color: #222;
    letter-spacing: 1px;
}
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 32px 24px;
}
.product-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: box-shadow 0.2s, transform 0.2s;
    padding-bottom: 18px;
}
.product-card:hover {
    box-shadow: 0 6px 24px rgba(30,144,255,0.13);
    transform: translateY(-4px) scale(1.03);
}
.product-img {
    width: 100%;
    height: 220px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid #f0f0f0;
}
.product-img img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.product-info {
    padding: 18px 10px 0 10px;
    width: 100%;
    text-align: center;
}
.product-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #222;
}
.product-price {
    color: #1e90ff;
    font-size: 1rem;
    margin-bottom: 12px;
    font-weight: 500;
}
.buy-btn {
    background: linear-gradient(90deg, #1e90ff 60%, #38b6ff 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 0;
    width: 100%;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.buy-btn:hover {
    background: linear-gradient(90deg, #38b6ff 60%, #1e90ff 100%);
}
@media (max-width: 700px) {
    .container-header, .product-main {
        padding: 10px;
    }
    .section-title {
        font-size: 1.2rem;
    }
    .product-img {
        height: 140px;
    }
}
/* BANNER TRANG CHỦ */
.banner-user {
    width: 100%;
    min-height: 260px;
    background: linear-gradient(90deg, #1e90ff 60%, #38b6ff 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 36px;
    box-shadow: 0 4px 24px rgba(30,144,255,0.07);
    position: relative;
    overflow: hidden;
}
.banner-content {
    color: #fff;
    text-align: center;
    z-index: 2;
    padding: 36px 16px;
}
.banner-content h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 12px;
    letter-spacing: 1px;
    text-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.banner-content p {
    font-size: 1.2rem;
    margin-bottom: 22px;
    font-weight: 400;
    text-shadow: 0 1px 4px rgba(0,0,0,0.07);
}
.banner-btn {
    background: #fff;
    color: #1e90ff;
    padding: 12px 32px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(30,144,255,0.09);
    transition: background 0.2s, color 0.2s;
    border: none;
    outline: none;
    display: inline-block;
}
.banner-btn:hover {
    background: #1e90ff;
    color: #fff;
}
</style>