
<?php
require_once __DIR__ . '/../configs/env.php';

$action = $_GET['action'] ?? '/';


require_once PATH_CONTROLLER . 'AdminOrderController.php';
require_once PATH_CONTROLLER . 'AdminUserController.php';

// Tạo kết nối PDO
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);


if (isset($_GET['controller']) && $_GET['controller'] === 'admin_user') {
    $controller = new AdminUserController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['change_role'])) {
            $controller->changeRole($_POST['user_id'], $_POST['role']);
        } elseif (isset($_POST['toggle_lock'])) {
            $controller->toggleLock($_POST['user_id']);
        }
    }
    $controller->index();
    return;
}

match ($action) {
    '/'                 => (new HomeController)->index(),
    'admin_orders'      => (new AdminOrderController($pdo))->index(),
};