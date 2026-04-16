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
$reviews = [];
$rv = $conn->query("SELECT r.*, u.name as user_name FROM product_reviews r JOIN users u ON r.user_id=u.id WHERE r.product_id = $id ORDER BY r.created_at DESC");
if ($rv && $rv->num_rows > 0) {
    while ($row = $rv->fetch_assoc()) $reviews[] = $row;
}

// Kiểm tra quyền đánh giá: chỉ người đã mua mới được đánh giá
$can_review = false;
if (isset($_SESSION['user'])) {
    $user_id = (int)$_SESSION['user']['id'];
    // Kiểm tra user đã mua sản phẩm này chưa
    $checkOrder = $conn->query("SELECT 1 FROM order_details od JOIN orders o ON od.order_id = o.id WHERE o.user_id = $user_id AND od.product_id = $id LIMIT 1");
    if ($checkOrder && $checkOrder->num_rows > 0) {
        $can_review = true;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">
 
</head>

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

      <form class="search-box" action="index.php" method="GET" onsubmit="return handleSearch()">
    <input type="text" id="search-input" name="keyword" placeholder="Tìm sản phẩm...">
    <button type="submit">🔍</button>
     </form>

        <div class="cart">
            <a href="cart.php">🛒</a>
                <a href="order_history.php" class="btn btn-outline-primary" style="padding:6px 16px;font-size:15px;border-radius:6px;font-weight:500;">Đơn hàng</a>


        </div>
    </div>

</header>

    <div class="banner-slider" style="max-width:1200px;height:220px;margin:30px auto 0 auto;position:relative;overflow:hidden;border-radius:14px;box-shadow:0 4px 24px 0 rgba(0,0,0,0.09);">
        <div class="slider-wrapper" id="slider-wrapper" style="display:flex;transition:transform 0.7s cubic-bezier(.4,0,.2,1);width:400%;height:100%;">
            <img src="<?= BASE_URL ?>/assets/images/banner1.jpg" style="width:100%;height:100%;object-fit:cover;aspect-ratio:16/5;" alt="Banner 1">
            <img src="<?= BASE_URL ?>/assets/images/banner3.jpg" style="width:100%;height:100%;object-fit:cover;aspect-ratio:16/5;" alt="Banner 3">
            <img src="<?= BASE_URL ?>/assets/images/banner4.jpg" style="width:100%;height:100%;object-fit:cover;aspect-ratio:16/5;" alt="Banner 4">
            <img src="<?= BASE_URL ?>/assets/images/banner5.jpg" style="width:100%;height:100%;object-fit:cover;aspect-ratio:16/5;" alt="Banner 5">
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
    function handleSearch() {
    var keyword = document.getElementById('search-input').value.trim();

    if (keyword === "") {
        // KHÔNG cho submit → ở nguyên trang chi tiết
        return false;
    }

    return true; // có chữ thì cho đi index
}
</script>
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
        <div class="product-detail-mainbox">
            <div class="product-detail-img">
                <img src="<?= BASE_ASSETS_UPLOADS . $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="product-detail-info">
                <h2><?= htmlspecialchars($product['name']) ?></h2>
                <div class="product-detail-price">Giá: <?= number_format($product['price']) ?>đ</div>
                <form class="product-detail-btns" id="add-to-cart-form">
                    <div id="cart-toast" style="display:none;position:fixed;top:30px;right:30px;z-index:9999;background:#1976d2;color:#fff;padding:16px 32px;border-radius:8px;box-shadow:0 2px 12px rgba(30,144,255,0.13);font-size:1.1rem;font-weight:600;transition:all .3s;">Đã thêm vào giỏ hàng!</div>
                    <label for="quantity" class="product-detail-label">Số lượng:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" style="width:70px;padding:8px 10px;border-radius:6px;border:1px solid #ccc;font-size:16px;">
                    <button type="submit" class="btn-add-cart">Thêm vào giỏ</button>
                    <button type="button" class="btn-buy" id="buy-now">Mua ngay</button>
                    <a href="index.php" class="btn-back">Quay lại</a>
                </form>

                <script>
                // ===== THÊM VÀO GIỎ HÀNG (CHUẨN) + TOAST + ĐẾM SỐ LƯỢNG =====
                function showCartToast(msg, success = true) {
                    var toast = document.getElementById('cart-toast');
                    toast.textContent = msg;
                    toast.style.background = success ? '#1976d2' : '#e53935';
                    toast.style.display = 'block';
                    setTimeout(() => { toast.style.display = 'none'; }, 1800);
                }
                function updateCartCount() {
                    fetch('getcartcount.php')
                        .then(res => res.json())
                        .then(data => {
                            var badge = document.getElementById('cart-count-badge');
                            if (badge) badge.textContent = data.count > 0 ? data.count : '';
                        });
                }
                document.getElementById('add-to-cart-form').onsubmit = function(e) {
                    e.preventDefault();
                    var quantity = document.getElementById('quantity').value;
                    fetch('<?= BASE_URL ?>/controllers/cart.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'product_id=<?= $id ?>&quantity=' + encodeURIComponent(quantity)
                    })
                    .then(res => res.json())
                    .then(data => {
                        showCartToast(data.message || '🛒 Đã thêm vào giỏ hàng!', data.success);
                        updateCartCount();
                    })
                    .catch(() => {
                        showCartToast('❌ Có lỗi xảy ra!', false);
                    });
                };
                // ===== NÚT MUA NGAY (CHUẨN) =====
                document.getElementById('buy-now').onclick = function() {
                    var quantity = document.getElementById('quantity').value;
                    window.location.href = 'checkout.php?id=<?= $product['id'] ?>&quantity=' + quantity;
                };
                // Cập nhật số lượng giỏ hàng khi vào trang
                document.addEventListener('DOMContentLoaded', updateCartCount);
                    <!-- ...existing code... -->
                // ===== GỬI ĐÁNH GIÁ =====
                var reviewForm = document.getElementById('review-form');
                if (reviewForm) {
                    reviewForm.onsubmit = function(e) {
                        e.preventDefault();
                        var formData = new FormData(reviewForm);
                        var msg = document.getElementById('review-msg');
                        msg.textContent = '';
                        fetch('<?= BASE_URL ?>/controllers/review.php', {
                            method: 'POST',
                            body: new URLSearchParams([...formData])
                        })
                        .then(res => res.json())
                        .then(data => {
                            msg.textContent = data.message;
                            msg.style.color = data.success ? '#388e3c' : '#e53935';
                            if (data.success) setTimeout(() => location.reload(), 800);
                        });
                    }
                }
                </script>
            <!-- Đánh giá & bình luận -->
            <div style="max-width:700px;margin:40px 0 0 0;background:#fff;border-radius:10px;box-shadow:0 2px 12px #e3eafc;padding:32px 28px 24px 28px;">
                <h3 style="margin-bottom:18px;color:#1976d2;font-size:1.3rem;">Đánh giá & bình luận</h3>
                <div id="review-list">
                    <?php if ($reviews): foreach ($reviews as $r): ?>
                        <div style="margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #eee;">
                            <b><?= htmlspecialchars($r['user_name']) ?></b> - <span style="color:#fbc02d;"> <?= str_repeat('★', (int)$r['rating']) . str_repeat('☆', 5-(int)$r['rating']) ?> </span>
                            <div style="margin:4px 0 0 0;"> <?= nl2br(htmlspecialchars($r['comment'])) ?> </div>
                            <div style="font-size:12px;color:#888;"> <?= date('d/m/Y H:i', strtotime($r['created_at'])) ?> </div>
                        </div>
                    <?php endforeach; else: ?>
                        <div>Chưa có đánh giá nào.</div>
                    <?php endif; ?>
                </div>
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($can_review): ?>
                        <form id="review-form" style="margin-top:10px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                            <input type="hidden" name="product_id" value="<?= $id ?>">
                            <label>Đánh giá: </label>
                            <select name="rating" required style="margin:0 8px 0 0;">
                                <option value="">Chọn sao</option>
                                <option value="5">★★★★★ - Tuyệt vời</option>
                                <option value="4">★★★★☆ - Tốt</option>
                                <option value="3">★★★☆☆ - Trung bình</option>
                                <option value="2">★★☆☆☆ - Kém</option>
                                <option value="1">★☆☆☆☆ - Rất tệ</option>
                            </select>
                            <input type="text" name="comment" placeholder="Viết bình luận..." style="width:220px;padding:6px 10px;border-radius:6px;border:1px solid #ccc;" required>
                            <button type="submit" class="btn-buy" style="padding:8px 18px;font-size:15px;">Gửi</button>
                            <span id="review-msg" style="margin-left:10px;color:#388e3c;font-weight:500;"></span>
                        </form>
                    <?php else: ?>
                        <div style="margin-top:12px;color:#888;">Chỉ khách đã mua sản phẩm này mới được đánh giá.</div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="margin-top:12px;color:#888;">Vui lòng <a href="login.php">đăng nhập</a> để đánh giá.</div>
                <?php endif; ?>
            </div>
            </div>
        </div>

        <!-- Sản phẩm khác -->
        <div style="max-width:1200px;margin:48px auto 0 auto;">
            <h3 style="margin-bottom:18px;color:#1976d2;font-size:1.3rem;">Sản phẩm khác</h3>
            <div class="product-grid">
            <?php
            $rel = $conn->query("SELECT * FROM products WHERE id != $id ORDER BY RAND() LIMIT 4");
            if ($rel && $rel->num_rows > 0):
                while ($sp = $rel->fetch_assoc()): ?>
                <div class="product-card">
                    <a href="product_detail.php?id=<?= $sp['id'] ?>" style="text-decoration:none;color:#222;display:block;">
                        <div class="product-img"><img src="<?= BASE_ASSETS_UPLOADS . $sp['image'] ?>" alt="<?= htmlspecialchars($sp['name']) ?>" style="width:100%;height:180px;object-fit:cover;border-radius:8px;"></div>
                        <div class="product-info">
                            <div class="product-name"> <?= htmlspecialchars($sp['name']) ?> </div>
                            <div class="product-price"> <?= number_format($sp['price']) ?>đ </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; endif; ?>
            </div>
        </div>

        <style>
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
            height: 180px;
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
        </style>
   <style>
        .product-detail-mainbox { max-width: 1200px; margin: 36px auto 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 24px 0 rgba(0,0,0,0.07); display: flex; gap: 38px; padding: 38px 38px 38px 38px; align-items: flex-start; }
        .product-detail-img { flex: 0 0 420px; display: flex; align-items: flex-start; justify-content: center; }
        .product-detail-img img { width: 400px; height: 400px; object-fit: cover; border-radius: 10px; box-shadow: 0 2px 8px 0 rgba(0,0,0,0.08); border: 1px solid #eee; background: #fafafa; }
        .product-detail-info { flex: 1; text-align: left; }
        .product-detail-info h2 { margin-top: 0; color: #1976d2; font-size: 2rem; font-weight: 700; }
        .product-detail-price { font-size: 2rem; color: #e53935; font-weight: bold; margin: 18px 0 22px 0; }
        .product-detail-btns { margin-top: 28px; display: flex; align-items: center; gap: 16px; }
        .btn-buy { background: #1976d2; color: #fff; border: none; border-radius: 6px; padding: 14px 38px; font-size: 18px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-buy:hover { background: #125ea7; }
        .btn-add-cart { background: #fff; color: #1976d2; border: 2px solid #1976d2; border-radius: 6px; padding: 13px 28px; font-size: 17px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-add-cart:hover { background: #1976d2; color: #fff; }
        .btn-back { background: #90a4ae; color: #fff; border: none; border-radius: 6px; padding: 10px 22px; font-size: 15px; margin-left: 12px; text-decoration: none; }
        .btn-back:hover { background: #78909c; }
        .product-detail-label { font-weight: 500; color: #444; margin-right: 10px; }
        .product-detail-select { padding: 8px 14px; border-radius: 6px; border: 1px solid #ccc; font-size: 16px; }
    </style>
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
</html>
