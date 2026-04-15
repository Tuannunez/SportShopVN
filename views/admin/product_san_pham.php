<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';
// Kiểm tra quyền admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
// Xử lý xóa sản phẩm
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
    header('Location: product_san_pham.php');
    exit;
}
$sql = "SELECT * FROM products ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>
<div class="sidebar">
    <h2>SportShopVN</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="product_san_pham.php" class="active">Sản phẩm</a>
    <a href="#">Danh mục</a>
    <a href="admin_orders.php">Đơn hàng</a>
    <a href="#">User</a>
    <a href="logout.php">Đăng xuất</a>
</div>
<div class="main">
    <h1>Quản lý sản phẩm</h1>
    <a href="product_add.php" class="btn btn-success" style="margin-bottom: 16px;">Thêm sản phẩm</a>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Ảnh</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= number_format($row['price']) ?>đ</td>
                <td>
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?= BASE_ASSETS_UPLOADS . $row['image'] ?>" alt="<?= htmlspecialchars($row['name']) ?>" width="60">
                    <?php else: ?>
                        <span>Không có ảnh</span>
                    <?php endif; ?>
                </td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="product_edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="5">Chưa có sản phẩm nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<style>
.admin-container { padding: 32px; }
.admin-table { width: 100%; border-collapse: collapse; background: #fff; }
.admin-table th, .admin-table td { border: 1px solid #ddd; padding: 8px; text-align: center; }
.admin-table th { background: #f4f6f9; }
.btn { padding: 6px 14px; border: none; border-radius: 4px; text-decoration: none; color: #fff; }
.btn-success { background: #28a745; }
.btn-warning { background: #ffc107; color: #222; }
.btn-danger { background: #dc3545; }
.btn-sm { font-size: 13px; padding: 4px 10px; }
</style>
