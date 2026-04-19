<?php
require_once __DIR__ . '/../../configs/env.php';

// Xóa danh mục nếu có yêu cầu
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM categories WHERE id = $delete_id");
    header('Location: category_list.php');
    exit;
}

// Lấy danh sách danh mục
$result = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý danh mục</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>
<div class="sidebar">
    <h2>SportShopVN</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="product_san_pham.php">Sản phẩm</a>
    <a href="category_list.php" class="active">Danh mục</a>
    <a href="admin_orders.php">Đơn hàng</a>
    <a href="admin_users.php">Tài khoản</a>
    <a href="logout.php">Đăng xuất</a>
</div>
<div class="main">
    <h1>Quản lý danh mục</h1>
    <a href="category_add.php" class="btn btn-success" style="margin-bottom: 16px;">Thêm danh mục</a>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>
                    <a href="category_edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa danh mục này?')">Xóa</a>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="3">Chưa có danh mục nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>
    /* ===== CATEGORY TABLE ===== */
.admin-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    margin-bottom: 30px;
}
.admin-table th, .admin-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    text-align: left;
    font-size: 16px;
}
.admin-table th {
    background: #1976d2;
    color: #fff;
    font-weight: 600;
}
.admin-table tr:last-child td {
    border-bottom: none;
}
.admin-table tr:hover {
    background: #f4f8fb;
}
.btn {
    display: inline-block;
    padding: 7px 16px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 15px;
    margin-right: 6px;
    transition: background 0.2s;
}
.btn-success {
    background: #43a047;
    color: #fff;
}
.btn-success:hover {
    background: #388e3c;
}
.btn-warning {
    background: #ffa000;
    color: #fff;
}
.btn-warning:hover {
    background: #ff8f00;
}
.btn-danger {
    background: #e53935;
    color: #fff;
}
.btn-danger:hover {
    background: #b71c1c;
}
.btn-secondary {
    background: #bdbdbd;
    color: #333;
}
.btn-secondary:hover {
    background: #757575;
    color: #fff;
}
.admin-form {
    background: #fff;
    padding: 24px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    max-width: 400px;
    margin-bottom: 30px;
}
.admin-form .form-group {
    margin-bottom: 18px;
}
.admin-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
}
.admin-form input[type="text"] {
    width: 100%;
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 16px;
}
</style>
</body>
</html>
