
<?php
require_once __DIR__ . '/../configs/env.php';

$action = $_GET['action'] ?? '/';

require_once PATH_CONTROLLER . 'AdminOrderController.php';

// Tạo kết nối PDO
$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
$pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);

match ($action) {
    '/'                 => (new HomeController)->index(),
    'admin_orders'      => (new AdminOrderController($pdo))->index(),
};