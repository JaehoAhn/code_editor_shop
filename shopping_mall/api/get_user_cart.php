<?php
require_once "C:/xampp/htdocs/lib/include.php";

$user_no = $_GET["user_no"];

$product_list = array();
$SQL = "SELECT products_tb.pro_no, products_tb.pro_name, products_tb.pro_price, 
products_tb.pro_rate, products_tb.pro_seller, cart_tb.purch_opt, cart_tb.purch_quant 
from products_tb 
join cart_tb 
on products_tb.pro_no = cart_tb.pro_no 
where cart_tb.user_no = :user_no";
$params = array(
    ":user_no" => $user_no
);
$db -> query($SQL, $params);
while($db->next_record()) {
    $row = $db->Record;

    $product_list[] = array(
        "pro_no" => $row["pro_no"],
        "pro_name" => $row["pro_name"],
        "pro_price" => $row["pro_price"],
        "pro_rate" => $row["pro_rate"],
        "pro_seller" => $row["pro_seller"],
        "purch_opt" => $row["purch_opt"],
        "purch_quant" => $row["purch_quant"]
    );
};


echo json_encode($product_list);

?>