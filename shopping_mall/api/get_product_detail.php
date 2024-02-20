<?php
require_once "C:/xampp/htdocs/lib/include.php";

$no = $_GET["pro_no"];

$product_list = array();
$SQL = "SELECT * FROM PRODUCTS_TB ";
$SQL .= "WHERE PRO_NO = :no";
$params = array(
    ":no" => $no
);

$db->query($SQL, $params);

$db->next_record();
$row = $db->Record;

$product_list = array(
    "pro_no" => $row["pro_no"],
    "pro_name" => $row["pro_name"],
    "pro_price" => $row["pro_price"],
    "pro_rate" => $row["pro_rate"],
    "pro_seller" => $row["pro_seller"],
    "pro_img_url" => $row["pro_img_url"],
    "pro_desc" => $row["pro_desc"]
);


echo json_encode($product_list);

?>