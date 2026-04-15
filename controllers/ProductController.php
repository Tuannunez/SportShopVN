<?php 
function product_index() {
    $keyword = $_GET['keyword'] ??'';
    $location = $_GET['location'] ?? '';
    $category_id = $_GET['category_id'] ?? '';

    $list_products = get_products_filter($keyword, $location, $category_id);

    include "views/users/index.php";
}