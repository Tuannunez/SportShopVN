<?php
require_once __DIR__ . '/../../configs/env.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$error = '';
$category = null;

if ($id > 0) {
    $result = $conn->query("SELECT * FROM categories WHERE id = $id");
    $category = $result ? $result->fetch_assoc() : null;
    if (!$category) {
        echo '<div style="color:red;margin:40px;">Không tìm thấy danh mục!</div>';
        exit;
    }
} else {
    echo '<div style="color:red;margin:40px;">Thiếu ID danh mục!</div>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        $error = 'Tên danh mục không được để trống!';
    } else {
        $stmt = $conn->prepare("UPDATE categories SET name=? WHERE id=?");
        $stmt->bind_param('si', $name, $id);
        if ($stmt->execute()) {
            header('Location: category_list.php');
            exit;
        } else {
            $error = 'Lỗi khi cập nhật danh mục!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa danh mục</title>
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
    <h1>Sửa danh mục</h1>
    <?php if ($error): ?>
        <div style="color:red; margin-bottom:12px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" class="admin-form">
        <div class="form-group">
            <label>Tên danh mục</label>
            <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
        </div>
        <button type="submit" class="btn btn-warning">Cập nhật</button>
        <a href="category_list.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<style>
    .admin-form {
    max-width: 400px;
    margin: 32px auto;
    background: #fff;
    padding: 28px 32px 24px 32px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.admin-form .form-group {
    margin-bottom: 18px;
}

.admin-form label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #2f3542;
}

.admin-form input[type=\"text\"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    background: #f8f9fa;
}

.admin-form .btn {
    padding: 10px 22px;
    border: none;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    margin-right: 8px;
    transition: background 0.2s;
}

.admin-form .btn-warning {
    background: #ffb300;
    color: #fff;
}

.admin-form .btn-warning:hover {
    background: #ffa000;
}

.admin-form .btn-secondary {
    background: #b0bec5;
    color: #263238;
}

.admin-form .btn-secondary:hover {
    background: #90a4ae;
}
</style>
</body>
</html>
