<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'Home' ?></title>

    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-xxl bg-light justify-content-center">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-uppercase" href="<?= BASE_URL ?>"><b>Home</b></a>
            </li>
        </ul>
    </nav>


    <div class="container">
        <h1 class="mt-3 mb-3"><?= $title ?? 'Home' ?></h1>

        <?php
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Nếu là admin thì chào admin, nếu là user thì chuyển hướng sang giao diện user
        if (isset($_SESSION['admin'])) {
            echo '<div class="user-greeting"> Xin chào, <b>' . htmlspecialchars($_SESSION['admin']['name']) . '</b>!</div>';
        } elseif (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/views/users/index.php');
            exit;
        } else {
            echo '<div style="margin-bottom:18px;">'
                . '<a class="btn btn-primary me-2" href="' . BASE_URL . '/views/users/login.php">Đăng nhập</a>'
                . '<a class="btn btn-success" href="' . BASE_URL . '/views/users/register.php">Đăng ký</a>'
                . '</div>';
        }
        ?>

        <div class="row">
            <?php
            if (isset($view)) {
                require_once PATH_VIEW . $view . '.php';
            }
            ?>
        </div>
    </div>


<style>
    .user-greeting {
        background: #f0f9ff;
        border: 1px solid #b6e0fe;
        color: #0a3d62;
        font-size: 18px;
        border-radius: 8px;
        padding: 12px 20px;
        margin-bottom: 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        display: inline-block;
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: background 0.2s;
    }
    .user-greeting b {
        color: #0074d9;
    }
</style>

</body>

</html>