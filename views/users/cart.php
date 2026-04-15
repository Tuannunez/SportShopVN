
<?php
session_start();
require_once __DIR__ . '/../../configs/env.php';
$cart = $_SESSION['cart'] ?? [];
// Tự động bổ sung ảnh cho sản phẩm chưa có image
$cart_changed = false;
foreach ($cart as $id => $item) {
    if (empty($item['image'])) {
        $result = $conn->query("SELECT image FROM products WHERE id = " . (int)$id);
        if ($result && $row = $result->fetch_assoc()) {
            $cart[$id]['image'] = $row['image'];
            $cart_changed = true;
        }
    }
}
if ($cart_changed) {
    $_SESSION['cart'] = $cart;
}
?>

<?php include 'header_user.php'; ?>

<div class="banner-user">
    <div class="banner-content">
        <h1>Giỏ hàng của bạn</h1>
        <p>Kiểm tra và thanh toán các sản phẩm bạn đã chọn!</p>
    </div>
</div>

<div class="cart-main">
<?php if (empty($cart)): ?>
    <p style="text-align:center;font-size:1.2rem;color:#888;margin:40px 0;">Giỏ hàng đang trống</p>
<?php else: ?>

<table class="cart-table">
    <tr>
        <th>Ảnh</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Tổng</th>
        <th>Hành động</th>
    </tr>

    <?php 
    $total = 0;
    foreach ($cart as $id => $item): 
        $qty = isset($item['quantity']) ? $item['quantity'] : (isset($item['qty']) ? $item['qty'] : 1);
        $subtotal = $item['price'] * $qty;
        $total += $subtotal;
    ?>
    <tr>
        <td>
            <img src="<?= isset($item['image']) ? (defined('BASE_ASSETS_UPLOADS') ? BASE_ASSETS_UPLOADS : '/assets/images/uploads/') . $item['image'] : '/assets/images/no-image.png' ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width:64px;height:64px;object-fit:cover;border-radius:8px;box-shadow:0 2px 8px #eee;">
        </td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td><?= number_format($item['price']) ?>đ</td>

        <td style="min-width:110px;">
            <button class="cart-btn" onclick="updateCart(<?= $id ?>, 'decrease')">-</button>
            <span class="cart-qty"><?= $qty ?></span>
            <button class="cart-btn" onclick="updateCart(<?= $id ?>, 'increase')">+</button>
        </td>

        <td><?= number_format($subtotal) ?>đ</td>

        <td>
            <button class="cart-btn remove" onclick="updateCart(<?= $id ?>, 'remove')">Xóa</button>
            <button class="cart-btn buy" onclick="buyNow(<?= $id ?>, <?= $qty ?>)">Mua</button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h3 style="text-align:right;margin:24px 0 0 0;font-size:1.3rem;">💰 Tổng tiền: <span style="color:#e53935;"><?= number_format($total) ?>đ</span></h3>


<?php endif; ?>
<div style="text-align:center;margin:32px 0 0 0;">
    <a href="index.php" style="display:inline-block;background:#1e90ff;color:#fff;padding:12px 32px;border-radius:8px;font-size:1.1rem;font-weight:600;text-decoration:none;box-shadow:0 2px 8px #eee;transition:background 0.2s;">← Quay lại trang chủ</a>
</div>
</div>

<style>
.cart-main {
    max-width: 1100px;
    margin: 36px auto 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px 0 rgba(0,0,0,0.07);
    padding: 38px 38px 38px 38px;
}
.cart-table {
    width: 100%;
    border-collapse: collapse;
    background: #fafcff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px 0 rgba(30,144,255,0.07);
}
.cart-table th, .cart-table td {
    padding: 16px 10px;
    text-align: center;
    font-size: 1.08rem;
}
.cart-table th {
    background: linear-gradient(90deg, #1e90ff 60%, #38b6ff 100%);
    color: #fff;
    font-weight: 700;
    border-bottom: 2px solid #e3eafc;
}
.cart-table tr:nth-child(even) {
    background: #f4f8fd;
}
.cart-table tr:hover {
    background: #e3eafc;
}
.cart-btn {
    background: #1e90ff;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 7px 16px;
    font-size: 1rem;
    font-weight: 600;
    margin: 0 4px;
    cursor: pointer;
    transition: background 0.2s;
}
.cart-btn:hover {
    background: #125ea7;
}
.cart-btn.remove {
    background: #e53935;
}
.cart-btn.remove:hover {
    background: #b71c1c;
}
.cart-btn.buy {
    background: #43a047;
}
.cart-btn.buy:hover {
    background: #2e7031;
}
.cart-qty {
    display: inline-block;
    min-width: 32px;
    font-weight: 700;
    font-size: 1.1rem;
}
@media (max-width: 700px) {
    .cart-main { padding: 10px; }
    .cart-table th, .cart-table td { padding: 8px 2px; font-size: 0.95rem; }
}
</style>

<script>
function updateCart(id, action) {
    fetch('/SportShopVN/views/users/update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + id + '&action=' + action
    })
    .then(res => res.json())
    .then(data => {
        location.reload();
    })
    .catch(err => {
        console.log('Lỗi:', err);
    });
}
function buyNow(id, qty) {
    window.location.href = 'checkout.php?id=' + id + '&quantity=' + qty;
}
</script>