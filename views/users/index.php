<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';
// Nếu chưa đăng nhập, không chuyển hướng mà chỉ ẩn thông tin user
$isLogin = isset($_SESSION['user']);
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">


<header class="user-header">
    
    <!-- dòng trên -->
  <div class="top-bar">
    <?php if (isset($_SESSION['user'])): ?>
        Xin chào, <b><?= htmlspecialchars($_SESSION['user']['name']) ?></b>
        <a class="logout" href="logout.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?')">Đăng xuất</a>
    <?php else: ?>
        <a class="login-btn" href="login.php">Đăng nhập</a>
        <a class="register-btn" href="register.php">Đăng ký</a>
    <?php endif; ?>
</div>

    <!-- dòng dưới -->
    <div class="main-header">
        <div class="logo">SportShopVN</div>

        <form class="search-box" action="index.php" method="GET">
            <input type="text" name="keyword" placeholder="Tìm sản phẩm...">
            <button type="submit">🔍</button>
        </form>

        <div class="cart">
            <a href="cart.php">🛒</a>
        </div>
    </div>

</header>


<div class="banner-slider" style="max-width:1200px;height:220px;margin:30px auto 0 auto;position:relative;overflow:hidden;border-radius:14px;box-shadow:0 4px 24px 0 rgba(0,0,0,0.09);">
    <div class="slider-wrapper" id="slider-wrapper" style="display:flex;transition:transform 0.7s cubic-bezier(.4,0,.2,1);width:400%;height:100%;">
        <img src="<?= BASE_URL ?>/assets/images/banner1.jpg" style="width:100%;height:100%;object-fit:cover;aspect-ratio:16/5;" alt="Banner 1">
        <img src="<?= BASE_URL ?>/assets/images/banner3.jpg" style="width:100%;height:100%;object-fit:cover;aspect-ratio:16/5;" alt="Banner 3">
        <img src="<?= BASE_URL ?>/assets/images/banner5.jpg" style="width:100%;height:100%;object-fit:cover;aspect-ratio:16/5;" alt="Banner 5">
        <img src="<?= BASE_URL ?>/assets/images/banner4.jpg" style="width:100%;height:100%;object-fit:cover;aspect-ratio:16/5;" alt="Banner 4">
    </div>
    <button id="prev-banner" style="position:absolute;top:50%;left:18px;transform:translateY(-50%);background:rgba(0,0,0,0.3);color:#fff;border:none;border-radius:50%;width:38px;height:38px;font-size:22px;cursor:pointer;z-index:2;">&#10094;</button>
    <button id="next-banner" style="position:absolute;top:50%;right:18px;transform:translateY(-50%);background:rgba(0,0,0,0.3);color:#fff;border:none;border-radius:50%;width:38px;height:38px;font-size:22px;cursor:pointer;z-index:2;">&#10095;</button>
    <div style="position:absolute;bottom:16px;left:50%;transform:translateX(-50%);display:flex;gap:8px;z-index:2;">
        <span class="dot" style="width:12px;height:12px;border-radius:50%;background:#fff;opacity:0.7;display:inline-block;cursor:pointer;" data-index="0"></span>
        <span class="dot" style="width:12px;height:12px;border-radius:50%;background:#fff;opacity:0.7;display:inline-block;cursor:pointer;" data-index="1"></span>
        <span class="dot" style="width:12px;height:12px;border-radius:50%;background:#fff;opacity:0.7;display:inline-block;cursor:pointer;" data-index="2"></span>
        <span class="dot" style="width:12px;height:12px;border-radius:50%;background:#fff;opacity:0.7;display:inline-block;cursor:pointer;" data-index="3"></span>
    </div>
</div>
<script>
const slider = document.getElementById('slider-wrapper');
const dots = document.querySelectorAll('.dot');
let current = 0;
let total = 4;
let autoSlide;
function showSlide(idx) {
    current = (idx + total) % total;
    slider.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((d,i)=>d.style.opacity = i===current ? '1' : '0.7');
}
function nextSlide() { showSlide(current+1); }
function prevSlide() { showSlide(current-1); }
document.getElementById('next-banner').onclick = nextSlide;
document.getElementById('prev-banner').onclick = prevSlide;
dots.forEach((d,i)=>d.onclick=()=>showSlide(i));
function startAuto() { autoSlide = setInterval(nextSlide, 3500); }
function stopAuto() { clearInterval(autoSlide); }
slider.parentElement.addEventListener('mouseenter', stopAuto);
slider.parentElement.addEventListener('mouseleave', startAuto);
showSlide(0); startAuto();
</script>

<main class="product-main">
    <h2 class="section-title">Sản phẩm mới</h2>
    <div class="product-grid">
        <?php
        // Lấy danh sách sản phẩm từ database
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 20";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <div class="product-card">
            <div class="product-img">
                <img src="<?= !empty($row['image']) ? BASE_ASSETS_UPLOADS . $row['image'] : 'https://via.placeholder.com/220x220?text=No+Image' ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            </div>
            <div class="product-info">
                <div class="product-name"><?= htmlspecialchars($row['name']) ?></div>
                <div class="product-price">Giá: <?= number_format($row['price']) ?>đ</div>
                <a href="product_detail.php?id=<?= $row['id'] ?>" class="detail-btn">Xem chi tiết</a>
                <button class="buy-btn">Mua ngay</button>
            <style>
            .detail-btn {
                display: inline-block;
                background: #fff;
                color: #1976d2;
                border: 1.5px solid #1976d2;
                border-radius: 6px;
                padding: 7px 18px;
                font-size: 15px;
                font-weight: 500;
                margin-right: 8px;
                margin-bottom: 6px;
                text-decoration: none;
                transition: background 0.2s, color 0.2s;
            }
            .detail-btn:hover {
                background: #1976d2;
                color: #fff;
            }
            </style>
            </div>
        </div>
        <?php endwhile; else: ?>
            <div>Chưa có sản phẩm nào.</div>
        <?php endif; ?>
    </div>
</main>
<style>
    
body {
    background: #f4f6f9;
    margin: 0;
    font-family: Arial, sans-serif;
}
/* HEADER */
.user-header {
    background: #222;
    color: #fff;
}

/* dòng trên */
.top-bar {
    text-align: right;
    padding: 8px 40px;
    font-size: 14px;
}

/* dòng dưới */
.main-header {
    display: flex;
    align-items: center;
    padding: 12px 40px;
    gap: 20px;
}

/* logo */
.logo {
    font-size: 24px;
    font-weight: bold;
    min-width: 180px;
}

/* search */
.search-box {
    flex: 1;
    display: flex;
}

.search-box input {
    width: 100%;
    padding: 10px;
    border: none;
    outline: none;
    border-radius: 4px 0 0 4px;
}

.search-box button {
    background: #ff7337;
    border: none;
    padding: 10px 16px;
    color: #fff;
    cursor: pointer;
    border-radius: 0 4px 4px 0;
}

/* cart */
.cart {
    min-width: 60px;
    text-align: right;
}

.cart a {
    font-size: 24px;
    color: #fff;
    text-decoration: none;
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