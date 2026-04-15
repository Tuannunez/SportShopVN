<?php
function get_products_filter($keyword = '', $location = '', $category_id = '') {
    global $conn;

    $sql = "SELECT * FORM products WHERE 1=1";

    if(!empty($keyword)) {
        $sql .="AND name LIKE '%$keyword%'";
    }

    if (!empty($location)){
        $sql .="AND location = '$location'";
    }

    if (!empty($category_id)){
        $sql .= "AND category_id ='$category_id'";
    }

    $sql .="ORDER BY id DESC";

    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)){
        $data[] = $row;
    }
    return $data;
}
?>