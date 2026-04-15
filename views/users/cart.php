<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
?>

<h2>🛒 Giỏ hàng của bạn</h2>

<?php if (empty($cart)): ?>
    <p>Giỏ hàng đang trống</p>
<?php else: ?>

<table border="1" cellpadding="10" width="100%">
    <tr>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Tổng</th>
        <th>Hành động</th>
    </tr>

    <?php 
    $total = 0;
    foreach ($cart as $id => $item): 
        $subtotal = $item['price'] * $item['qty'];
        $total += $subtotal;
    ?>
    <tr>
        <td><?= $item['name'] ?></td>
        <td><?= number_format($item['price']) ?>đ</td>

        <td>
            <button onclick="updateCart(<?= $id ?>, 'decrease')">-</button>
            <?= $item['qty'] ?>
            <button onclick="updateCart(<?= $id ?>, 'increase')">+</button>
        </td>

        <td><?= number_format($subtotal) ?>đ</td>

        <td>
            <button onclick="updateCart(<?= $id ?>, 'remove')">Xóa</button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h3>💰 Tổng tiền: <?= number_format($total) ?>đ</h3>

<?php endif; ?>

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
</script>