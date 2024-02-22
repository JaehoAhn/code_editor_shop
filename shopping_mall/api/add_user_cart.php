<?php

require_once "C:/xampp/htdocs/lib/include.php";

$user_no = $_GET['user_no'];
$pro_no = $_GET['pro_no'];
$pro_option = $_GET['option'];
$quant = (int)$_GET['quant'];


$proceed = false;
$params = array();
$msg = '';
$SQL = "INSERT INTO CART_TB (user_no, pro_no, purch_opt, purch_quant) VALUES (:user_no, :pro_no, :purch_opt, :purch_quant)";
$params = array(
    ":user_no" => $user_no,
    ":pro_no" => $pro_no,
    ":purch_opt"=> $pro_option,
    ":purch_quant" => $quant
);
if($db->query($SQL, $params)) {
    $proceed = true;
    $msg = "Add Cart Success.";
} else {
    $msg = "Add Cart Fail.";
}

$result = array(
    "proceed" => $proceed,
    "msg" => $msg
);

echo json_encode($result);

?>