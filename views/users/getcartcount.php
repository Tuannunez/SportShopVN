<?php
session_start();
$count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $count += isset($item['quantity']) ? $item['quantity'] : (isset($item['qty']) ? $item['qty'] : 1);
    }
}
echo json_encode(['count' => $count]);