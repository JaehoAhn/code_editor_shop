<?php

require_once "C:/xampp/htdocs/lib/include.php";

$user_no = $_GET['user_no'];
$pro_no = $_GET['pro_no'];
$purch_date = $_GET['curr_date'];

// echo $user_no;
// exit();


$proceed = false;
$params = array();
$msg = '';
$SQL = "INSERT INTO USER_PURCHASE_TB (user_no, pro_no, purch_date) VALUES (:user_no, :pro_no, :purch_date)";
$params = array(
    ":user_no" => $user_no,
    ":pro_no" => $pro_no,
    ":purch_date" => $purch_date,
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