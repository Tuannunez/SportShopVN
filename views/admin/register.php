
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $errors = [];
    if (empty($name)) {
        $errors[] = "Vui lòng nhập tên.";
    }
    if (empty($email)) {
        $errors[] = "Vui lòng nhập email.";
    }
    if (empty($password)) {
        $errors[] = "Vui lòng nhập mật khẩu.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
    }

    if (empty($errors)) {
        // Kiểm tra email đã tồn tại chưa
        $checkEmail = $conn->query("SELECT id FROM users WHERE email = '" . $conn->real_escape_string($email) . "'");
        if ($checkEmail && $checkEmail->num_rows > 0) {
            $errors[] = "Bạn đã có tài khoản";
        } else {
            $sql = "INSERT INTO users(name,email,password,role) 
                    VALUES('$name','$email','$password','admin')";

            if ($conn->query($sql)) {
                echo '<script>alert("Đăng ký thành công! Chuyển sang trang đăng nhập..."); window.location.href = "login.php";</script>';
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký ADMIN</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
</head>
<body>

<div class="form-container">
    <h2>Đăng ký</h2>

    <!-- Hiển thị lỗi nếu có -->



    <form method="POST" id="registerForm" autocomplete="off">
        <input name="name" id="name" placeholder="Tên" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">
        <div id="error-name" style="color:red; text-align:left; font-size:14px;">
            <?php if (isset($errors) && in_array('Vui lòng nhập tên.', $errors)): ?>Vui lòng nhập tên.<?php endif; ?>
        </div>
        <br>

        <input name="email" id="email" placeholder="Email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
        <div id="error-email" style="color:red; text-align:left; font-size:14px;">
            <?php if (isset($errors) && in_array('Vui lòng nhập email.', $errors)): ?>Vui lòng nhập email.<br><?php endif; ?>
            <?php if (isset($errors) && in_array('Bạn đã có tài khoản', $errors)): ?>Bạn đã có tài khoản<?php endif; ?>
        </div>
        <br>

        <input type="password" name="password" id="password" placeholder="Mật khẩu">
        <div id="error-password" style="color:red; text-align:left; font-size:14px;">
            <?php if (isset($errors) && in_array('Vui lòng nhập mật khẩu.', $errors)): ?>Vui lòng nhập mật khẩu.<br><?php endif; ?>
            <?php if (isset($errors) && in_array('Mật khẩu phải có ít nhất 6 ký tự.', $errors)): ?>Mật khẩu phải có ít nhất 6 ký tự.<?php endif; ?>
        </div>
        <br>

        <button name="register">Đăng ký</button>
    </form>

    <a href="login.php">Đăng nhập</a>
</div>

</body>
<script>
// Ẩn lỗi khi người dùng nhập đúng
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    nameInput.addEventListener('input', function() {
        if (nameInput.value.trim() !== '') {
            document.getElementById('error-name').innerText = '';
        }
    });
    emailInput.addEventListener('input', function() {
        if (emailInput.value.trim() !== '') {
            document.getElementById('error-email').innerText = '';
        }
    });
    passwordInput.addEventListener('input', function() {
        const val = passwordInput.value;
        let msg = '';
        if (val === '') {
            msg = 'Vui lòng nhập mật khẩu.';
        } else if (val.length < 6) {
            msg = 'Mật khẩu phải có ít nhất 6 ký tự.';
        }
        document.getElementById('error-password').innerText = msg;
        if (val.length >= 6) {
            document.getElementById('error-password').innerText = '';
        }
    });
});
</script>
</html>