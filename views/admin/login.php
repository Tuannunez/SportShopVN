<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

$login_error = '';
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    // Nếu đăng nhập thành công sẽ chuyển hướng, nên chỉ thông báo khi đúng
    // Đoạn này sẽ được thay thế bằng thông báo khi đăng nhập thành công

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='admin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin'] = $admin;
        echo '<script>alert("Đăng nhập thành công! Chuyển sang trang quản trị..."); window.location.href = "dashboard.php";</script>';
        exit;
    } else {
        // Kiểm tra email có tồn tại không
        $checkEmail = $conn->query("SELECT id FROM users WHERE email = '" . $conn->real_escape_string($email) . "'");
        if ($checkEmail && $checkEmail->num_rows == 0) {
            $login_error = "Tài khoản chưa đăng ký.";
        } else {
            $login_error = "Sai mật khẩu hoặc bạn không phải admin.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập ADMIN</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>


<div class="form-container">
    <h2>Đăng nhập</h2>


    <form method="POST">
        <input name="email" placeholder="Email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"><br>
        <input type="password" name="password" placeholder="Mật khẩu"><br>
        <?php if (!empty($login_error)): ?>
            <div style="color:red; text-align:left; font-size:14px; margin: 8px 0;">
                <?= $login_error ?>
            </div>
        <?php endif; ?>
        <button name="login">Đăng nhập</button>
    </form>

    <a href="register.php">Đăng ký</a>
</div>

</body>
</html>