<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

// Biến lưu lỗi
$error = '';
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kiểm tra email đã tồn tại chưa
    $checkEmail = $conn->query("SELECT id FROM users WHERE email = '" . $conn->real_escape_string($email) . "'");
    if ($checkEmail && $checkEmail->num_rows > 0) {
        $error = 'Email đã tồn tại!';
    } else {
        $sql = "INSERT INTO users(name,email,password,role) 
                VALUES('$name','$email','$password','user')";
        if ($conn->query($sql)) {
            echo "<script>alert('Đăng ký thành công! Chuyển sang trang đăng nhập...'); window.location.href = 'login.php';</script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký User</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">
</head>
<body>

<div class="form-container">
    <h2>Đăng ký User</h2>


    <form method="POST">
        <input name="name" placeholder="Tên"><br>
        <input name="email" placeholder="Email"><br>
        <?php if (!empty($error)): ?>
            <div style="color:red; font-size:14px; margin-bottom:8px;"> <?= $error ?> </div>
        <?php endif; ?>
        <input type="password" name="password" placeholder="Mật khẩu"><br>

        <button name="register">Đăng ký</button>
    </form>

    <a href="login.php">Đã có tài khoản? Đăng nhập</a>
</div>

</body>
</html>