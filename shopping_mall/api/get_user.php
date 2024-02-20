<?php

require_once "C:/xampp/htdocs/lib/include.php";

$user_no = $_GET['user_no'];

$userList = array();
$userHist= array();

// $SQL_info = "SELECT * FROM USERS_TB";
// $db->query($SQL_info);
// while($db->next_record()) {
//     $row = $db->Record;

//     $userList[] = array(
//         "userNo" => $row["user_no"],
//         "firstName" => $row["first_name"],
//         "lastName" => $row["last_name"],
//         "email" => $row["email"],
//         "pswd" => $row["pswd"],
//     );
// }

$purchase_items = array();

$SQL = "SELECT * FROM user_purchase_tb JOIN products_tb ON user_purchase_tb.pro_no = products_tb.pro_no WHERE user_no = :user_no";
$params = array(
    ":user_no" => $user_no
);

$db -> query($SQL, $params);
while($db->next_record()) {
    $row = $db->Record;

    $purchase_items[] = array(
        "pro_no" => $row["pro_no"],
        "pro_name" => $row["pro_name"],
        "pro_price" => $row["pro_price"],
        "pro_seller" => $row["pro_seller"],
        "purch_no" => $row["purch_no"],
        "purch_date" => $row["purch_date"]
    );
};

echo json_encode($purchase_items);
?>