<?php

require_once "C:/xampp/htdocs/lib/include.php";

$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$email = $_GET['email'];
$pswd = $_GET['pswd'];


$proceed = false;
$params = array();
$msg = '';
$SQL = "INSERT INTO USERS_TB (first_name, last_name, email, pswd) VALUES (:first_name, :last_name, :email, :pswd)";
$params = array(
    ":first_name" => $first_name,
    ":last_name" => $last_name,
    ":email" => $email,
    ":pswd" => $pswd
);
if($db->query($SQL, $params)) {
    $proceed = true;
    $msg = "회원가입이 완료되었습니다.";
} else {
    $msg = "회원가입에 실패하였습니다.";
}

$result = array(
    "proceed" => $proceed,
    "msg" => $msg
);

echo json_encode($result);

?>