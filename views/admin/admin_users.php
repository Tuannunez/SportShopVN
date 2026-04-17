<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);
$conn->set_charset('utf8mb4');

// Xử lý đổi vai trò
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_role'])) {
        $userId = (int)$_POST['user_id'];
        $role = $conn->real_escape_string($_POST['role']);
        $conn->query("UPDATE users SET role='$role' WHERE id=$userId");
    } elseif (isset($_POST['toggle_lock'])) {
        $userId = (int)$_POST['user_id'];
        $result = $conn->query("SELECT locked FROM users WHERE id=$userId");
        $locked = 0;
        if ($row = $result->fetch_assoc()) {
            $locked = $row['locked'] ? 0 : 1;
        }
        $conn->query("UPDATE users SET locked=$locked WHERE id=$userId");
    }
    // Reload lại trang để tránh submit lại form khi refresh
    header('Location: admin_users.php');
    exit;
}

$users = [];
$result = $conn->query("SELECT id, name, email, role, locked FROM users ORDER BY id ASC");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý tài khoản - Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <h2>SportShopVN</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="product_san_pham.php">Sản phẩm</a>
    <a href="#">Danh mục</a>
    <a href="admin_orders.php">Đơn hàng</a>
    <a href="admin_users.php" class="active">Tài khoản</a>
    <a href="logout.php">Đăng xuất</a>
</div>

<div class="main">

<h1 class="admin-title">Quản lý tài khoản</h1>
<div class="admin-table-container">
<table class="admin-table-user">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên đăng nhập</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
                <form method="post" class="inline-form">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <select name="role" class="role-select">
                        <option value="user" <?= $user['role']==='user'?'selected':'' ?>>User</option>
                        <option value="admin" <?= $user['role']==='admin'?'selected':'' ?>>Admin</option>
                    </select>
                    <button type="submit" name="change_role" class="btn-action btn-role">Đổi vai trò</button>
                </form>
            </td>
            <td>
                <span class="status-badge <?= $user['locked'] ? 'locked' : 'active' ?>">
                    <?= $user['locked'] ? 'Đã khóa' : 'Hoạt động' ?>
                </span>
            </td>
            <td>
                <form method="post" class="inline-form">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button type="submit" name="toggle_lock" class="btn-action btn-lock">
                        <?= $user['locked'] ? 'Mở khóa' : 'Khóa' ?>
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>
</body>
<style>
    /* ===== Title ===== */
.admin-title {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 20px;
}

/* ===== Container ===== */
.admin-table-container {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* ===== Table ===== */
.admin-table-user {
    width: 100%;
    border-collapse: collapse;
    font-size: 15px;
}

.admin-table-user thead {
    background: #f5f6fa;
}

.admin-table-user th {
    text-align: left;
    padding: 12px 10px;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #eaeaea;
}

.admin-table-user td {
    padding: 12px 10px;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

/* Hover */
.admin-table-user tbody tr:hover {
    background: #f9fafc;
}

/* ===== Form inline ===== */
.inline-form {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ===== Select ===== */
.role-select {
    padding: 5px 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
    outline: none;
}

/* ===== Buttons ===== */
.btn-action {
    padding: 5px 10px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 13px;
    transition: 0.2s;
}

/* Đổi vai trò */
.btn-role {
    background: #3498db;
    color: white;
}
.btn-role:hover {
    background: #2980b9;
}

/* Khóa / mở khóa */
.btn-lock {
    background: #e74c3c;
    color: white;
}
.btn-lock:hover {
    background: #c0392b;
}

/* ===== Status ===== */
.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

/* Hoạt động */
.status-badge.active {
    background: #eafaf1;
    color: #27ae60;
}

/* Đã khóa */
.status-badge.locked {
    background: #fdecea;
    color: #e74c3c;
}
</style>
</html>