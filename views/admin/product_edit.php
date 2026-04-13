<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Lấy thông tin sản phẩm
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = null;
$error = '';
if ($id > 0) {
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    $product = $result->fetch_assoc();
    if (!$product) {
        $error = 'Không tìm thấy sản phẩm.';
    }
} else {
    $error = 'Thiếu ID sản phẩm.';
}

// Xử lý cập nhật sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $product) {
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $image = $product['image'];

    // Xử lý upload ảnh mới nếu có
    if (!empty($_FILES['image']['name'])) {
        try {
            $image = upload_file('', $_FILES['image']);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    if (!$error && $name && $price > 0) {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, image=? WHERE id=?");
        $stmt->bind_param('sdsi', $name, $price, $image, $id);
        $stmt->execute();
        header('Location: product_san_pham.php');
        exit;
    } else if (!$error) {
        $error = 'Vui lòng nhập đầy đủ thông tin.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>
<div class="sidebar">
    <h2>SportShopVN</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="product_san_pham.php" class="active">Sản phẩm</a>
    <a href="#">Danh mục</a>
    <a href="#">Đơn hàng</a>
    <a href="#">User</a>
    <a href="logout.php">Đăng xuất</a>
</div>
<div class="main">
    <h1>Sửa sản phẩm</h1>
    <?php if ($error): ?>
        <div style="color:red; margin-bottom:12px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($product): ?>
    <form method="post" enctype="multipart/form-data" class="admin-form">
        <div class="form-group">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Giá</label>
            <input type="number" name="price" min="0" step="1000" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>
        <div class="form-group">
            <label>Ảnh sản phẩm</label>
            <?php if (!empty($product['image'])): ?>
                <img src="<?= BASE_ASSETS_UPLOADS . $product['image'] ?>" alt="Ảnh hiện tại" width="80" style="display:block; margin-bottom:8px;">
            <?php endif; ?>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-warning">Cập nhật</button>
        <a href="product_san_pham.php" class="btn btn-secondary">Quay lại</a>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f4f6f9;
}
.main {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding-top: 40px;
}
.admin-form {
    max-width: 420px;
    width: 100%;
    margin: 0 auto;
    background: #fff;
    padding: 32px 28px 24px 28px;
    border-radius: 14px;
    box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08);
    border: 1px solid #e3e3e3;
}
.admin-form h1 {
    text-align: center;
    margin-bottom: 24px;
    color: #222;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 7px;
    font-weight: 500;
    color: #333;
}
.form-group input[type="text"],
.form-group input[type="number"],
.form-group input[type="file"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #cfd8dc;
    border-radius: 6px;
    font-size: 15px;
    background: #f9fafb;
    transition: border 0.2s;
}
.form-group input:focus {
    border: 1.5px solid #1976d2;
    outline: none;
}
.btn {
    padding: 8px 22px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    margin-right: 8px;
}
.btn-warning {
    background: #ffb300;
    color: #222;
}
.btn-warning:hover {
    background: #ffa000;
    color: #fff;
}
.btn-secondary {
    background: #90a4ae;
    color: #fff;
    text-decoration: none;
}
.btn-secondary:hover {
    background: #78909c;
}
img[alt="Ảnh hiện tại"] {
    border-radius: 8px;
    box-shadow: 0 2px 8px 0 rgba(0,0,0,0.07);
    border: 1px solid #e0e0e0;
}
</style>
