<?php

require_once "C:/xampp/htdocs/lib/include.php";

$user_no = $_GET['user_no'];
$pro_no = $_GET['pro_no'];
$purch_date = $_GET['curr_date'];
$pro_option = $_GET['option'];
$quant = (int)$_GET['quant'];

$proceed = false;
$params = array();
$msg = '';
$SQL = "INSERT INTO USER_PURCHASE_TB (user_no, pro_no, purch_date, purch_opt, purch_quant) VALUES (:user_no, :pro_no, :purch_date, :purch_opt, :purch_quant)";
$params = array(
    ":user_no" => $user_no,
    ":pro_no" => $pro_no,
    ":purch_date" => $purch_date,
    ":purch_opt"=> $pro_option,
    ":purch_quant" => $quant
);
if($db->query($SQL, $params)) {
    $proceed = true;
    $msg = "Purchase Success.";
} else {
    $msg = "Purchase Fail.";
}

$result = array(
    "proceed" => $proceed,
    "msg" => $msg
);

echo json_encode($result);

?>