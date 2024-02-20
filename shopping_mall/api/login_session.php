<?php

require_once "C:/xampp/htdocs/lib/include.php";
session_start();

$uemail = $_GET['uemail'];
$upswd = $_GET['upswd'];

$SQL = "SELECT * FROM USERS_TB WHERE EMAIL = :uemail AND PSWD = :upswd";
$params = array(
    ":uemail" => $uemail,
    ":upswd" => $upswd
);

$db->query($SQL, $params);

$loginCnt = $db->nf();

$proceed = true;
$msg = '';

if($loginCnt > 0) {
    $db->next_record();
    $row = $db->Record;
    
    //if login password is equal to db password.
    if($upswd == $row["pswd"]) {
        $proceed = true;
        $msg = 'Login Success.';

        $_SESSION["user"]["u_no"] = $row["user_no"];
        $_SESSION["user"]["email"] = $row["email"];
        $_SESSION["user"]["first_name"] = $row["first_name"];
        $_SESSION["user"]["last_name"] = $row["last_name"];
    }

    else {
        $proceed = false;
        $msg = 'Login Fail';
    }
} 

else {
    $proceed = false;
    $msg = 'Login Fail';
}


$result = array(
    "proceed" => $proceed,
    "msg" => $msg,
    "uno" => $row['user_no'],
    "uemail" => $row["email"],
    "ufname" => $row["first_name"],
    "ulname" => $row["last_name"]

);

echo json_encode($result);

?>