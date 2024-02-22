<?php
require_once "C:/xampp/htdocs/lib/include.php";

$user_no = $_GET["user_no"];

$product_list = array();
$SQL = "DELETE FROM CART_TB WHERE user_no = :user_no";
$params = array(
    ":user_no" => $user_no
);

$db -> query($SQL, $params);


?>