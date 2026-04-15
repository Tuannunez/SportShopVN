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
        <form class="search-box" action="index.php" method="GET" onsubmit="return handleSearch ? handleSearch() : true;">
            <input type="text" id="search-input" name="keyword" placeholder="Tìm sản phẩm...">
            <button type="submit">🔍</button>
        </form>
        <div class="cart" style="display:flex;align-items:center;gap:12px;">
            <a href="cart.php" title="Giỏ hàng">🛒</a>
            <a href="order_history.php" class="btn btn-outline-primary" style="padding:6px 16px;font-size:15px;border-radius:6px;font-weight:500;">Đơn hàng</a>
        </div>
    </div>
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
  </style>
</header>
