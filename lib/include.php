<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
header("Access-Control-Allow-Origin: *"); // 모든 도메인에서의 요청을 허용하려면 '*'를 사용합니다.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); // 허용할 HTTP 메소드를 지정합니다.
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$_SERVER["DOCUMENT_ROOT"] = str_replace("\\","/", $_SERVER["DOCUMENT_ROOT"]);
define("_LIB_PATH_"         , str_replace("\\","/", dirname(__FILE__)) . "/");
define("_LIB_URL_"          , str_replace("\\", "/", str_replace($_SERVER["DOCUMENT_ROOT"], "", _LIB_PATH_))); // str_replace("\\", "/", "") : Microsoft Windows에서 경로를 \로 표시되는것을 /로 변경....
define("_ROOT_PATH_", $_SERVER["DOCUMENT_ROOT"]);

require_once _LIB_PATH_ . "db_pdooci.php";
require_once _LIB_PATH_ . "db_local.php";
require_once _LIB_PATH_ . "user.class.php";
require_once _LIB_PATH_ . "page.class.php";

$server         = "61.41.17.239";
$service_name   = "ORCL";
$sid            = "ORCL";
$port           = 1521;
$dbtns          = "(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = $server)(PORT = $port)) (CONNECT_DATA = (SERVICE_NAME = $service_name) (SID = $sid)))";
define("_DB_Host_Oracle_"    , false);
define("_DB_User_Oracle_"    , "jhahn2401");
define("_DB_Pass_Oracle_"    , "jhahn2401a");
define("_DB_Name_Oracle_"    , $dbtns);


$db = new DB;
?>