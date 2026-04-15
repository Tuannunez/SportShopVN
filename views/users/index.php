<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

// 1. Lấy dữ liệu lọc và sắp xếp
$keyword = $_GET['keyword'] ?? '';
$location = $_GET['location'] ?? '';
$color = $_GET['color'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

// 2. Câu lệnh SQL lọc động
$sql = "SELECT * FROM products WHERE 1=1";
if (!empty($keyword)) $sql .= " AND name LIKE '%".mysqli_real_escape_string($conn, $keyword)."%'";
if (!empty($location)) $sql .= " AND location = '".mysqli_real_escape_string($conn, $location)."'";
if (!empty($color)) $sql .= " AND color = '".mysqli_real_escape_string($conn, $color)."'";

// Sắp xếp
if ($sort == 'sales') $sql .= " ORDER BY view_count DESC";
elseif ($sort == 'price_asc') $sql .= " ORDER BY price ASC";
elseif ($sort == 'price_desc') $sql .= " ORDER BY price DESC";
else $sql .= " ORDER BY id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>SportShopVN - Trang chủ</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">
    <style>
        body { background: #f5f5f5; margin: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        
        /* --- HEADER PHONG CÁCH SHOPEE (ĐÃ FIX TO & TRÀN) --- */
        .user-header { background: linear-gradient(-180deg, #f53d2d, #f63); color: #fff; padding-bottom: 10px; }
        .top-bar { display: flex; justify-content: flex-end; padding: 5px 40px; font-size: 13px; gap: 15px; }
        .top-bar a { color: #fff; text-decoration: none; }
        
        .main-header { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 10px 40px; 
            gap: 40px; /* Khoảng cách giữa logo và search */
        }
        
        .logo { font-size: 30px; font-weight: bold; color: #fff; text-decoration: none; flex-shrink: 0; }
        
        /* Ô tìm kiếm tràn màn hình */
        .search-box { 
            flex: 1; 
            display: flex; 
            background: #fff; 
            padding: 3px; 
            border-radius: 2px; 
            box-shadow: 0 1px 1px rgba(0,0,0,.1);
            max-width: 900px; /* Độ rộng tối đa để cân đối */
        }
        .search-box input { 
            width: 100%; 
            border: none; 
            outline: none; 
            padding: 10px 15px; 
            font-size: 15px; 
        }
        .search-box button { 
            background: #fb5533; 
            border: none; 
            padding: 0 25px; 
            color: #fff; 
            cursor: pointer; 
            border-radius: 2px;
            font-size: 18px;
        }

        /* Giỏ hàng to rõ */
        .cart { flex-shrink: 0; }
        .cart a { font-size: 35px; color: #fff; text-decoration: none; position: relative; }

        /* --- BANNER SLIDER --- */
        .banner-container { max-width: 1200px; margin: 20px auto; border-radius: 4px; overflow: hidden; height: 250px; }
        .slider-wrapper { display: flex; transition: transform 0.5s ease-in-out; height: 100%; }
        .slider-wrapper img { width: 100%; flex-shrink: 0; object-fit: cover; }

        /* --- CẤU TRÚC 2 CỘT --- */
        .main-layout { display: flex; max-width: 1200px; margin: 20px auto; gap: 20px; padding: 0 10px; }
        
        /* Sidebar lọc */
        .sidebar-filter { width: 190px; flex-shrink: 0; }
        .filter-group { margin-bottom: 25px; }
        .filter-title { font-weight: bold; font-size: 16px; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        .filter-item { display: block; margin-bottom: 12px; font-size: 14px; cursor: pointer; color: #333; }
        .filter-item input { margin-right: 10px; }

        /* Vùng sản phẩm */
        .product-content { flex: 1; }
        .sort-bar { 
            background: rgba(0,0,0,.03); 
            padding: 13px 20px; 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            border-radius: 2px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .sort-btn { background: #fff; border: none; padding: 7px 15px; cursor: pointer; border-radius: 2px; }
        .sort-btn.active { background: #ee4d2d; color: #fff; }

        /* Danh sách sản phẩm (Card của bạn) */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px; }
        .product-card { 
            background: #fff; 
            border: 1px solid transparent;
            transition: transform 0.1s, box-shadow 0.1s;
            padding-bottom: 15px;
            text-align: center;
        }
        .product-card:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 1px 20px rgba(0,0,0,.05);
            border-color: #ee4d2d;
        }
        .product-img { width: 100%; height: 190px; padding: 10px; box-sizing: border-box; }
        .product-img img { width: 100%; height: 100%; object-fit: contain; }
        .p-name { font-size: 13px; color: #333; margin: 10px; height: 32px; overflow: hidden; }
        .p-price { color: #ee4d2d; font-size: 16px; font-weight: 500; margin-bottom: 15px; }
        .btn-detail { display: block; color: #1e90ff; text-decoration: none; font-size: 12px; margin-bottom: 8px; }
        .btn-buy { background: #ee4d2d; color: #fff; border: none; padding: 8px 15px; border-radius: 2px; cursor: pointer; width: 80%; }
    </style>
</head>
<body>

<header class="user-header">
    <div class="top-bar">
        <?php if(isset($_SESSION['user'])): ?>
            <span>Chào, <?= $_SESSION['user']['name'] ?></span>
            <a href="logout.php">Đăng xuất</a>
        <?php else: ?>
            <a href="login.php">Đăng nhập</a>
            <a href="register.php">Đăng ký</a>
        <?php endif; ?>
    </div>
    <div class="main-header">
        <a href="index.php" class="logo">SportShopVN</a>
        
        <form class="search-box" action="index.php" method="GET">
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="SportShopVN bao ship 0Đ - Mua ngay!">
            <button type="submit">🔍</button>
        </form>

        <div class="cart">
            <a href="cart.php">🛒</a>
        </div>
    </div>
</header>

<div class="banner-container">
    <div class="slider-wrapper" id="mainSlider">
        <img src="<?= BASE_URL ?>/assets/images/banner1.jpg" alt="Banner 1">
        <img src="<?= BASE_URL ?>/assets/images/banner2.jpg" alt="Banner 2">
        <img src="<?= BASE_URL ?>/assets/images/banner3.jpg" alt="Banner 3">
    </div>
</div>

<div class="main-layout">
    <aside class="sidebar-filter">
        <div class="filter-title">📑 BỘ LỌC TÌM KIẾM</div>
        <form id="filterForm" method="GET" action="index.php">
            <div class="filter-group">
                <p style="font-weight: 500;">Nơi Bán</p>
                <?php $locs = ['Hà Nội', 'Đà Nẵng', 'Phú Thọ', 'TP.HCM']; foreach($locs as $l): ?>
                    <label class="filter-item">
                        <input type="radio" name="location" value="<?= $l ?>" <?= $location==$l?'checked':'' ?> onchange="this.form.submit()"> <?= $l ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="filter-group">
                <p style="font-weight: 500;">Màu sắc</p>
                <label class="filter-item"><input type="radio" name="color" value="Trắng" onchange="this.form.submit()"> Trắng</label>
                <label class="filter-item"><input type="radio" name="color" value="Xanh" onchange="this.form.submit()"> Xanh</label>
            </div>
            
            <a href="index.php" style="color: #ee4d2d; text-decoration: none; font-size: 13px;">Xóa tất cả</a>
        </form>
    </aside>

    <main class="product-content">
        <div class="sort-bar">
            <span>Sắp xếp theo</span>
            <button class="sort-btn <?= $sort=='newest'?'active':'' ?>" onclick="location.href='index.php?sort=newest'">Mới nhất</button>
            <button class="sort-btn <?= $sort=='sales'?'active':'' ?>" onclick="location.href='index.php?sort=sales'">Bán chạy</button>
            <select style="padding: 7px; border: 1px solid #ddd;" onchange="location.href='index.php?sort=' + this.value">
                <option value="">Giá</option>
                <option value="price_asc" <?= $sort=='price_asc'?'selected':'' ?>>Giá: Thấp đến Cao</option>
                <option value="price_desc" <?= $sort=='price_desc'?'selected':'' ?>>Giá: Cao đến Thấp</option>
            </select>
        </div>

        <div class="product-grid">
            <?php if($result->num_rows > 0): while($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="product-img">
                        <img src="<?= BASE_ASSETS_UPLOADS . $row['image'] ?>" alt="">
                    </div>
                    <div class="p-name"><?= htmlspecialchars($row['name']) ?></div>
                    <div class="p-price"><?= number_format($row['price']) ?>đ</div>
                    <a href="product_detail.php?id=<?= $row['id'] ?>" class="btn-detail">Xem chi tiết</a>
                    <button class="btn-buy">Mua ngay</button>
                </div>
            <?php endwhile; else: ?>
                <p>Không tìm thấy sản phẩm nào.</p>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
    // Script chạy slider tự động
    const wrapper = document.getElementById('mainSlider');
    let index = 0;
    setInterval(() => {
        index = (index + 1) % 3; // 3 là số ảnh banner
        wrapper.style.transform = `translateX(-${index * 100}%)`;
    }, 4000);
</script>

</body>
</html>