<?php
require_once "C:/xampp/htdocs/lib/include.php";

$searchInput = $_GET["searchInput"];
$side_level_rate = $_GET['side_level_rate'];
$side_level_price = $_GET['side_level_price'];

$product_list = array();
$SQL = "SELECT * FROM PRODUCTS_TB ";
$SQL .= "WHERE PRO_NAME LIKE '%{$searchInput}%'";

$SQL_add = "";

if ($side_level_rate == "low_high_rate" || $side_level_rate == "high_low_rate") {
    if ($side_level_rate == "low_high_rate") {
        $SQL_add = "ORDER BY PRO_RATE";
    }
    
    else {
        $SQL_add = "ORDER BY PRO_RATE DESC";
    }
}

if ($side_level_price == "low_high_price" || $side_level_price == "high_low_price") {
    if ($side_level_rate == "low_high_rate") {
        $SQL_add = "ORDER BY PRO_RATE";
    }
    
    elseif ($side_level_rate == "high_low_rate") {
        $SQL_add = "ORDER BY PRO_RATE DESC";
    }

    else {
        if ($side_level_price == "low_high_price") {
            $SQL_add = "ORDER BY PRO_PRICE";
        }
        
        else {
            $SQL_add = "ORDER BY PRO_PRICE DESC";
        }
    }
}

$SQL = $SQL . $SQL_add;

$db->query($SQL);

while($db->next_record()) {
    $row = $db->Record;

    $product_list[] = array(
        "pro_no" => $row["pro_no"],
        "pro_name" => $row["pro_name"],
        "pro_price" => $row["pro_price"],
        "pro_rate" => $row["pro_rate"],
        "pro_seller" => $row["pro_seller"],
        "pro_img_url" => $row["pro_img_url"],
        "pro_desc" => $row["pro_desc"]
    );
}

echo json_encode($product_list);

?>