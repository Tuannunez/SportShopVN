<?php
require_once __DIR__ . '/../../configs/env.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        $error = 'Tên danh mục không được để trống!';
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param('s', $name);
        if ($stmt->execute()) {
            header('Location: category_list.php');
            exit;
        } else {
            $error = 'Lỗi khi thêm danh mục!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm danh mục</title>
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
    <h1>Thêm danh mục</h1>
    <?php if ($error): ?>
        <div style="color:red; margin-bottom:12px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" class="admin-form">
        <div class="form-group">
            <label>Tên danh mục</label>
            <input type="text" name="name" required>
        </div>
        <button type="submit" class="btn btn-success">Thêm danh mục</button>
        <a href="category_list.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<style>
    .admin-form {
    background: #fff;
    padding: 24px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    max-width: 400px;
    margin: 40px auto 30px auto;
    /* căn giữa ngang */
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
.btn-secondary {
    background: #bdbdbd;
    color: #333;
}
.btn-secondary:hover {
    background: #757575;
    color: #fff;
}
</style>
</body>
</html>
