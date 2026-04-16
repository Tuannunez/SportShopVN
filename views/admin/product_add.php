<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

// Kiểm tra quyền admin
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Xử lý thêm sản phẩm
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $image = '';

    // Xử lý upload ảnh
    if (!empty($_FILES['image']['name'])) {
        try {
            $image = upload_file('', $_FILES['image']);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }

    if (!$error && $name && $price > 0) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sdss', $name, $price, $image, $description);
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
    <title>Thêm sản phẩm</title>
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
    <h1>Thêm sản phẩm</h1>
    <?php if ($error): ?>
        <div style="color:red; margin-bottom:12px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="admin-form">
        <div class="form-group">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Giá</label>
            <input type="number" name="price" min="0" step="1000" required>
        </div>
        <div class="form-group">
            <label>Ảnh sản phẩm</label>
            <input type="file" name="image" accept="image/*">
        </div>
        <div class="form-group">
            <label>Mô tả sản phẩm</label>
            <textarea name="description" rows="4" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Thêm sản phẩm</button>
        <a href="product_san_pham.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html>
<style>
.admin-form { max-width: 400px; margin: 0 auto; background: #fff; padding: 24px; border-radius: 8px; }
.form-group { margin-bottom: 16px; }
.form-group label { display: block; margin-bottom: 6px; }
.form-group input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
.btn-secondary { background: #888; color: #fff; text-decoration: none; margin-left: 8px; }
</style>
