<?php
require_once __DIR__ . '/../../configs/env.php';
require_once __DIR__ . '/../../configs/helper.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users 
            WHERE email='$email' AND password='$password' AND role='user'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $_SESSION['user'] = $user;

        header("Location: index.php");
    } else {
        echo "<script>alert('Sai tài khoản');</script>";
    }
}
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/user.css">

<div class="form-container">
    <h2>Đăng nhập User</h2>

    <form method="POST">
        <input name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="Mật khẩu"><br>

        <button name="login">Đăng nhập</button>
    </form>

    <a href="register.php">Chưa có tài khoản? Đăng ký</a>
</div>