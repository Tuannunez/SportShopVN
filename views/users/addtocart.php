<?php
session_start();

$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['qty'] += 1;
} else {
    $_SESSION['cart'][$id] = [
        'name' => $name,
        'price' => $price,
        'qty' => 1
    ];
}

// trả về JSON
echo json_encode([
    "status" => "success"
]);