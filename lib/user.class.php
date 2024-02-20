<?php
class User {
    var $uno;
    var $user_id;

    /**
     * PHP5용 Class 생성자
     */
    function __construct(){
        $this->User();
    }
    function __destruct(){
        // if(!$this->userDB->PConnect) {
          // unset($this->userDB);
        // }
    }
    /**
     * Class 생성자
     */
    function User() {
        $this->uno = $this->getUNo();
        $this->user_id = $this->getUserID();
    }
    /**
     * 사용자 정보 존재 여부
     * @Param $Auser_id {String} : 사용자 ID
     * @Rerurn {Boolean}
     */
    function isUser($Auser_id){
        global $userDB;

        $SQL = "SELECT COUNT(*) FROM VCMG_USER WHERE logon_cd = '{$Auser_id}'";
        $userDB->query($SQL);
        $userDB->next_record();
        if ($userDB->f(0) > 0) {
            return true;
        } 
        else {
            return false;
        }
    }
	/**
     * 사용자 정보 가져오기
     * @Param $Adb {Resource} : DB리소스 객체
	 * @Param $Auno {Int} : UNO
	 * @Return {Array} : 사용자 정보
     */
	function setDbLogin($Adb, $Auno)
	{
		if(isset($Adb) && $Adb && isset($Auno) && $Auno)
		{
			$SQL = "SELECT * FROM SYS_USER_SET WHERE uno = " . $Auno;
			$Adb->query($SQL);
			if ($Adb->nf() > 0) 
			{
				$Adb->next_record();
				if(isset($_SERVER) && $_SERVER["REMOTE_ADDR"] == "10.10.103.221")
				{
					//print_r($Adb);
				}
				$rec = $Adb->Record;
				$this->setLoginSessionMatch($rec, false);
			}
		}
	}
	/**
     * 사용자 정보 가져오기
     * @Param $Adb {Resource} : DB리소스
	 * @Param $Asid {Resource} : 그룹웨어 로그인 세션ID
	 * @Param $Auser_id {String} : 사용자ID
	 * @Param $AisRefresh {Boolean} : 새로고침 여부?
	 * @Return {String} : 로그인 사용자ID
     */
	function setGroupwareSSO($Adb, $Asid, $Auser_id, $AisRefresh = false)
	{
		if(isset($_SERVER) && $_SERVER["REMOTE_ADDR"] == "10.10.103.221")
		{
			$sid = $Asid; //$_REQUEST["sid"]
			$user_id = $Auser_id; //$_REQUEST["user_id"];
			$ip = $_SERVER["REMOTE_ADDR"];
			$to_date = date("Y-m-d");
			
			$check = md5($user_id . "/" . $to_date);
			$param01 = md5($ip);
			$param02 = md5($to_date);
					
			$curl = curl_init();
			$url = "https://gw.htenc.co.kr/api/login/check/";
			
			$param = array(
				'sid' => $sid
				, 'user_id' => $user_id
				, 'check' => $check
				, 'param01' => $param01
				, 'param02' => $param02
				, 'agent' => $_SERVER['HTTP_USER_AGENT']
			);
			
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POST => true,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_POSTFIELDS => $param,
				CURLOPT_HTTPHEADER => array(
					"cache-control: no-cache",
					'Accept: application/json',
					//'Content-Type: application/json'
				),
			));
			$result = curl_exec($curl);
			curl_close($curl);
			
			if(isset($result) && !is_null($result) && $result != "")
			{
				
				$contents = json_decode($result, true);
				
				if(isset($contents) && $contents != "" && array_key_exists("ResultType", $contents) && $contents["ResultType"] == "Success" && $contents["Value"])
				{
					try
					{
						//$userDB = new userDB;
						//$userDB = new CommonDB;
						$this->setDbLogin($Adb, $contents["Value"]);
					
					} catch(Exception $e){
						echo '<pre>';
						print_r($e);
						echo '</pre>';
					}
					
				}
				/*
				@session_start();
				$contents = json_decode($result, true);
				if(isset($contents) && !is_null($contents) && is_array($contents) && array_key_exists("user", $contents) )
				{
					foreach($contents["user"] as $_key => $_val)
					{
						$_SESSION["user"][$_key] = $_val;
					}
					//$_SESSION["user"] = $contents;
					//echo '<pre>';
					//print_r($_SESSION);
					//echo '</pre>';
					
				}
				*/
			}
		}
		$isLogin = @$_SESSION["user"]["user_id"];
		if($isLogin && $AisRefresh)
		{
			if(isset($_SERVER) && $_SERVER["HTTPS"] == "on")
			{
				$location = "https://";
			}
			else
			{
				$location = "http://";
			}
			$location .= $_SERVER['HTTP_HOST'];
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $location);
		}
		return $isLogin;
	}
	
	/**
     * 사용자 정보 매칭
     * @Param $result {Array} : 사용자 정보
	 * @Param $iswcf {Boolean} : WCF 타입 사용 여부
     */
	function setLoginSessionMatch($result, $iswcf = true)
	{
		global $checkAuth;
		global $userDB;
		
		unset($_SESSION["user"]);
		if(isset($result) && is_array($result))
		{
			if($iswcf == true){
				$this->uno = $result["Uno"];
				$this->user_id = $result["User_ID"];
				$_SESSION["user"]["uno"]       = $result["Uno"];
				$_SESSION["user"]["user_id"]   = $result["User_ID"];
				$_SESSION["user"]["user_name"] = $result["User_Name"];
				$_SESSION["user"]["team_id"]   = $result["Team_ID"];
				$_SESSION["user"]["team_name"] = $result["Team_Name"];
				$_SESSION["user"]["duty_id"]   = $result["Position_Code"];
				$_SESSION["user"]["duty_name"] = $result["Position_Name"];
				$_SESSION["user"]["sub_team_id"]   = $result["Sub_Team_ID"];
				$_SESSION["user"]["sub_team_name"] = $result["Sub_Team_Name"];
				$_SESSION["user"]["company_id"]   = $result["Company_ID"];
				$_SESSION["user"]["company_name"] = $result["Company_Name"];
				$_SESSION["user"]["is_mobile_gw"] = $result["IS_MOBILE_GW"];
				$_SESSION["user"]["is_use"] = $result["IS_USE"];
				$_SESSION["user"]["is_attend"] = $result["IS_ATTEND"];
			}
			else
			{
				$this->uno = $result["uno"];
				$this->user_id = $result["user_id"];
				$_SESSION["user"]["uno"]       = $result["uno"];
				$_SESSION["user"]["user_id"]   = $result["user_id"];
				$_SESSION["user"]["user_name"] = $result["user_name"];
				$_SESSION["user"]["team_id"]   = $result["team_id"];
				$_SESSION["user"]["team_name"] = $result["team_name"];
				$_SESSION["user"]["duty_id"]   = $result["duty_id"];
				$_SESSION["user"]["duty_name"] = $result["duty_name"];
				$_SESSION["user"]["sub_team_id"]   = $result["sub_team_id"];
				$_SESSION["user"]["sub_team_name"] = $result["sub_team_name"];
				$_SESSION["user"]["company_id"]   = $result["company_id"];
				$_SESSION["user"]["company_name"] = $result["company_name"];
				$_SESSION["user"]["is_mobile_gw"] = $result["is_mobile_gw"];
				$_SESSION["user"]["is_use"] = $result["is_use"];
				$_SESSION["user"]["is_attend"] = $result["is_attend"];
			}
			//권한 체크 여부
			if ($checkAuth && $result["Auth_List"]) 
			{
				foreach($result["Auth_List"] as $auth) {
					$_SESSION["user"][$auth] = "Y";
				}
				if(isset($userDB) && $userDB)
				{
					if (!in_array("TM", $_SESSION["user"])) {
						$SQL  = "SELECT user_id ";
						$SQL .= " FROM VCMG_GW_USERDEPT ";
						$SQL .= " WHERE user_id = " . $_SESSION["user"]["uno"] . " ";
						$SQL .= " AND DUTY_NM = '팀장' ";
						$userDB->query($SQL);
						if ($userDB->nf() > 0) {
							$_SESSION["user"]["TM"] = "Y";
						}
					}
				}
			}
			$_SESSION["user"]["HTTP_USER_AGENT"] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION["user"]["REMOTE_ADDR"] 	 = $_SERVER['REMOTE_ADDR'];
		}
	}
    /**
     * 로그인(로그인 폼 이용시)
     * @Param $Auser_id {String} : 사용자 ID
     * @Param $Auser_pwd {String} : 사용자 Password(기본값 : Null)
     * @Param &$Amsg {PString} : 메세지 문자열 => &변수이므로 그값을 그대로 가짐(포인터 변수)
     * @Return {Boolean}
     */
    function setLogin($Auser_id, $Auser_pwd = NULL, &$Amsg = NULL) {
        global $checkAuth;
        global $employeesOnly;

        $curl = curl_init();

        $url = "http://wcfservice.hi-techeng.co.kr/SINGINTEGRATIONSYSTEM/getloginresult";
        $param = array(
            'iLoginType' => 1, 
//             'strIp' => $_SERVER["REMOTE_ADDR"], 
            'strIp' => "61.33.147.32",
            'strId' => $Auser_id, 
            'strPwd' => $Auser_pwd, 
            'isDuplication' => 1
        );

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POST => true,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => json_encode($param),
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                'Accept: application/json',
                'Content-Type: application/json'
            ),
        ));

        $result = curl_exec($curl);
        curl_close($curl);
        if($result === false) {
            $Amsg = "로그인 중 오류가 발생하였습니다. 관리자에게 문의하세요.(" . curl_error($curl) . ")";
            return false;
        }
        else {
            $result = json_decode($result, true);
            if (!$result["isLogin"]) {
                $Amsg = $result["Error_Message"];
                return false;
            }

            if ($employeesOnly) {
                if ($result["IS_ATTEND"] == "N") {
                    $Amsg = "해당 시스템에 접근할 권한이 없습니다.";
                    return false;
                }
            }
			
			/**
            unset($_SESSION["user"]);
            $this->uno = $result["Uno"];
            $this->user_id = $result["User_ID"];
            $_SESSION["user"]["uno"]       = $result["Uno"];
            $_SESSION["user"]["user_id"]   = $result["User_ID"];
            $_SESSION["user"]["user_name"] = $result["User_Name"];
            $_SESSION["user"]["team_id"]   = $result["Team_ID"];
            $_SESSION["user"]["team_name"] = $result["Team_Name"];
            $_SESSION["user"]["duty_id"]   = $result["Position_Code"];
            $_SESSION["user"]["duty_name"] = $result["Position_Name"];
            $_SESSION["user"]["sub_team_id"]   = $result["Sub_Team_ID"];
            $_SESSION["user"]["sub_team_name"] = $result["Sub_Team_Name"];
            $_SESSION["user"]["company_id"]   = $result["Company_ID"];
            $_SESSION["user"]["company_name"] = $result["Company_Name"];
            $_SESSION["user"]["is_mobile_gw"] = $result["IS_MOBILE_GW"];
            $_SESSION["user"]["is_use"] = $result["IS_USE"];
            $_SESSION["user"]["is_attend"] = $result["IS_ATTEND"];

            //권한 체크 여부
            if ($checkAuth) {
                foreach($result["Auth_List"] as $auth) {
                    $_SESSION["user"][$auth] = "Y";
                }
                if (!in_array("TM", $_SESSION["user"])) {
                    global $userDB;

                    $SQL  = "SELECT user_id ";
                    $SQL .= " FROM VCMG_GW_USERDEPT ";
                    $SQL .= " WHERE user_id = " . $_SESSION["user"]["uno"] . " ";
                    $SQL .= " AND DUTY_NM = '팀장' ";
                    $userDB->query($SQL);
                    if ($userDB->nf() > 0) {
                        $_SESSION["user"]["TM"] = "Y";
                    }
                }
            }
            
			
			$_SESSION["user"]["HTTP_USER_AGENT"] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION["user"]["REMOTE_ADDR"] 	 = $_SERVER['REMOTE_ADDR'];
			*/
			
			
			
            try
            {
				$this->setLoginSessionMatch($result, true);
				$Amsg = "사용이 허가 되었습니다.";
                $this->setSaveSession_UserMemberAll();
            } catch (Exception $ex) {
                ;;
            }
    

            return true;
        }

        

        /*
        $wsdl = "http://wcfservice.hi-techeng.co.kr/SING INTEGRATION SYSTEM/Service1.svc?singleWsdl";
        $soapClient = new SoapClient($wsdl, array(
            'trace' => true,
            'encoding'=>'UTF-8',
            'exceptions'=>true,
            'cache_wsdl'=>WSDL_CACHE_NONE,
            'soap_version' => SOAP_1_1
        ));

        try {
//             $result = $soapClient->__getFunctions();
//             $result = $soapClient->__getTypes();
            $Auser_id = trim($Auser_id);
            if (!is_null($Auser_pwd)){
                $Auser_pwd = trim($Auser_pwd);
            }
//             $result = $soapClient->GetLoginResult(array('iLoginType' => 1, 'strIp' => $_SERVER["REMOTE_ADDR"], 'strId' => $Auser_id, 'strPwd' => $Auser_pwd, 'isDuplication' => 1));
            $result = $soapClient->GetLoginResult(array('iLoginType' => 1, 'strIp' => "61.33.147.32", 'strId' => $Auser_id, 'strPwd' => $Auser_pwd, 'isDuplication' => 1));
            $result = get_object_vars($result);
            $result = json_decode(reset($result), true);
            if (!$result["isLogin"]) {
                $Amsg = $result["Error_Message"];
                return false;
            }

//             @session_start();
            unset($_SESSION["user"]);
            $this->uno = $result["Uno"];
            $this->user_id = $result["User_ID"];
            $_SESSION["user"]["uno"]       = $result["Uno"];
            $_SESSION["user"]["user_id"]   = $result["User_ID"];
            $_SESSION["user"]["user_name"] = $result["User_Name"];
            $_SESSION["user"]["team_id"]   = $result["Team_ID"];
            $_SESSION["user"]["team_name"] = $result["Team_Name"];
            $_SESSION["user"]["duty_id"]   = $result["Position_Code"];
            $_SESSION["user"]["duty_name"] = $result["Position_Name"];
            $_SESSION["user"]["sub_team_id"]   = $result["Sub_Team_ID"];
            $_SESSION["user"]["sub_team_name"] = $result["Sub_Team_Name"];

            if ($checkAuth) {
                foreach($result["Auth_List"] as $auth) {
                    $_SESSION["user"][$auth] = "Y";
                }
                if (!in_array("TM", $_SESSION["user"])) {
                    global $userDB;

                    $SQL  = "SELECT user_id ";
                    $SQL .= "FROM VCMG_GW_USERDEPT ";
                    $SQL .= "WHERE user_id = " . $_SESSION["user"]["uno"] . " ";
                    $SQL .= "AND DUTY_NM = '팀장' ";
                    $userDB->query($SQL);
                    if ($userDB->nf() > 0) {
                        $_SESSION["user"]["TM"] = "Y";
                    }
                }
            }
            $Amsg = "사용이 허가 되었습니다.";
            return true;
        }
        catch (SoapFault $fault) {
//             print $fault . NEWLINE;
//             echo "Fault code: {$fault->faultcode}" . NEWLINE;
//             echo "Fault string: {$fault->faultstring}" . NEWLINE;
            if ($soapClient != null) {
                $soapClient = null;
            }
            $Amsg = "로그인 중 오류가 발생하였습니다. 관리자에게 문의하세요.(" . $fault->faultstring. ")";
            return false;
        }*/

//         global $db;
//         global $userDB;
//         global $coId;
//         global $checkAuth;

//         $Auser_id = trim($Auser_id);
//         if (!is_null($Auser_pwd)){
//             $Auser_pwd = trim($Auser_pwd);
//         }
//         $SQL  = "SELECT";
//         $SQL .= "  U.user_id AS uno";
//         $SQL .= ", U.logon_cd AS user_id";
//         $SQL .= ", U.user_nm AS user_name";
//         $SQL .= ", U.logon_pwd AS user_pwd";
//         $SQL .= ", CASE fire_yn WHEN '0' THEN CASE GW_USER_LEVEL WHEN 0 THEN 'N' ELSE CASE GW_USER_USE_YN WHEN 1 THEN 'Y' ELSE 'N' END END ELSE 'Y' END AS is_active";
//         $SQL .= ", D.dept_id AS team_id";
//         $SQL .= ", D.dept_nm AS team_name";
//         $SQL .= ", UD.grade AS duty_id";
//         $SQL .= ", GRA.cd_nm AS duty_name";
//         $SQL .= " FROM dbo.TCMG_USERDEPT AS UD ";
//         $SQL .= " INNER JOIN dbo.TCMG_USER AS U ON UD.user_id = U.user_id ";
//         $SQL .= " INNER JOIN dbo.TCMG_DEPT AS D ON UD.dept_id = D.dept_id AND D.use_yn = 1 ";
//         $SQL .= " INNER JOIN dbo.TCMG_CO AS C ON D.co_id = C.co_id ";
//         $SQL .= " LEFT OUTER JOIN dbo.FCMT_CD(1, {$coId}, 'GRADE') AS GRA ON GRA.cd_val = UD.grade ";
//         $SQL .= " WHERE D.co_id = {$coId} AND UD.hold_office IN (1, 2) ";
//         $SQL .= " AND logon_cd = '{$Auser_id}'";
//         $userDB->query($SQL);
//         $n = $userDB->nf();
//         if ($n == 1) {
//             $userDB->next_record();
//             $row = $userDB->Record;
//             //Fun::print_r($row);
//             if ($row["is_active"] == "N"){
//                 $Amsg = "사용이 허가된 사용자가 아닙니다. 관리자에게 문의하세요.";
//                 return false;
//             } 
//             if (!is_null($Auser_pwd) && ($row["is_active"] == "Y") && (strtolower($Auser_pwd) != strtolower($row["user_pwd"]))) {
//                 $Amsg = "사용자 비밀번호가 틀렸습니다. 다시 한번 확인하시기 바랍니다.";
//                 return false;
//             } 
//             try{
//                 @session_start();
//                 unset($_SESSION["user"]);
//                 unset($_COOKIE["uno"]);
//                 unset($_COOKIE["user_id"]);
//                 @setCookie("uno", $row["uno"], 0, "/");
//                 @setCookie("user_id", $row["user_id"], 0, "/");
//                 $this->uno = $row["uno"];
//                 $this->user_id = $row["user_id"];
//                 $_SESSION["user"]["uno"]       = $row["uno"];
//                 $_SESSION["user"]["user_id"]   = $row["user_id"];
//                 $_SESSION["user"]["user_name"] = $row["user_name"];
//                 $_SESSION["user"]["team_id"]   = $row["team_id"];
//                 $_SESSION["user"]["team_name"] = $row["team_name"];
//                 $_SESSION["user"]["duty_id"]   = $row["duty_id"];
//                 $_SESSION["user"]["duty_name"] = $row["duty_name"];

//                 if ($checkAuth) {
//                     $SQL  = "SELECT JOB_AUTH ";
//                     $SQL .= "FROM TIMESHEET.JOB_USER_SET ";
//                     $SQL .= "WHERE UNO = {$row["uno"]} AND IS_USE = 'Y' ";
//                     $db->query($SQL);
//                     if($db->nf() > 0){
//                         while($db->next_record()){
//                             $_SESSION["user"][$db->f("job_auth")] = "Y";
//                         }
//                     }
//                     $SQL  = "SELECT COUNT(*) AS CNT ";
//                     $SQL .= "FROM TIMESHEET.JOB_INFO ";
//                     $SQL .= "WHERE JOB_PM = {$row["uno"]} ";
//                     $n = $db->getOne($SQL);
//                     //echo $n;
//                     if($n > 0){
//                         //echo $n;
//                         $_SESSION["user"]["PM"] = "Y";
//                     }
//                     // $db->disconnect();
//                 }
//                 $Amsg = "사용이 허가 되었습니다.";
//                 return true;
//             } catch (Exception $e) {
//                 $Amsg = "사용자 정보 생성 중 오류가 발생하였습니다. 관리자에게 문의하세요.(" . $e->getMessage() . ")";
//                 return flase;
//             }
//         } 
//         else if ( $n > 1){
//             $Amsg = "사용자 정보가 2개이상 입니다. 관리자에게 문의하세요.";
//             return false;
//         } 
//         else {
//             $Amsg = "사용자 정보가 존재 하지 않습니다.";
//             return false;
//         }

//         return true;
    }
    /**
     * 로그인(GroupWare 이용 통해서 접속 시)
     * @Param $user_id {String} : 사용자 ID
     * @Param &$msg {PString} : 메세지 문자열 => &변수이므로 그값을 그대로 가짐(포인터 변수)
     * @Return {Boolean}
     */
    function setWebBizLogin($Auser_id, &$Amsg = NULL){
        global $userDB;
        global $coId;

        $Auser_id = trim($Auser_id);
        $Auser_pwd = "";
        $SQL  = "SELECT U.logon_pwd AS user_pwd ";
        $SQL .= "FROM dbo.TCMG_USERDEPT AS UD ";
        $SQL .= " INNER JOIN dbo.TCMG_USER AS U ON UD.user_id = U.user_id ";
        $SQL .= "WHERE co_id = {$coId} ";
        $SQL .= " AND UD.hold_office IN (1, 2) ";
        $SQL .= " AND U.logon_cd = '{$Auser_id}' ";
        $userDB->query($SQL);
        $n = $userDB->nf();
        if ($n == 1) {
            $userDB->next_record();
            $row = $userDB->Record;
            $Auser_pwd = $row["user_pwd"];
        }

        return $this->setLogin($Auser_id, $Auser_pwd, $Amsg);
    }
    /**
     * 사용자 UNO 정보
     * @Return {String}
     */
    function getUNo(){
        //$str = NULL;
        //$str = $_COOKIE["uno"];
        $str = $this->uno;
        if (!$str && is_array($_SESSION) && array_key_exists("user", $_SESSION) && array_key_exists("uno", $_SESSION["user"])){
            $str = $_SESSION["user"]["uno"];
        }
        if (!$str){
            $str = $this->uno;
        }
        $this->uno = $str;
        return $str;
    }
    /**
     * 사용자 USER ID 정보
     * @Return {String}
     */
    function getUserID(){
        //$str = "";
        //$str = $_COOKIE["user_id"];
        $str = $this->user_id;
        if (!$str && is_array($_SESSION) && array_key_exists("user", $_SESSION) && array_key_exists("uno", $_SESSION["user"])){
            $str = $_SESSION["user"]["user_id"];
        }
        if (!$str){
            $str = $this->user_id;
        }
        $this->user_id = $str;
        return $str;
    }
    /**
     * 사용자 정보
     * @Param $ACol {String} : 사용자 ID
     * @Return {String}
     */
    function getUserInfo($ACol){
        $ACol = strtolower($ACol);
        switch($ACol){
            case "uno" :
                $str = $this->getUNo();
                break;
            case "user_id" :
                $str = $this->getUserID();
                break;
            default :
                $str = $_SESSION["user"][$ACol];
        }
        return $str;
    }
    /**
     * 사용자 정보
     * @Param $ACol {String} : 사용자 ID
     * @Return {String}
     */
    function UserInfo($ACol){
        return $this->getUserInfo($ACol);
    }


    function getUserMemberInfo($uno = null)
    {
        global $userDB;

        $Result = null;
        if(isset($userDB) && is_object($userDB) && $userDB)
        {
            $_table_BIZ_USER_SET = "[dbo].[BIZ_USER_SET]";
            $SQL = "SELECT * FROM {$_table_BIZ_USER_SET} WHERE 1 = 1";
            if(isset($uno) && !is_array($uno) && $uno)
            {
                $SQL .= " AND uno = '{$uno}'";
            }
            $params = array();
            $userDB->query($SQL, $params);
            if($userDB->nf() > 0)
            {
                $Result = array();
                while($userDB->next_record()){
                    $row = $userDB->Record;
                    if(isset($row) && is_array($row) && array_key_exists("user_pwd", $row) && isset($row["user_pwd"]) && $row["user_pwd"])
                    {
                        $password = $row["user_pwd"];
                        $password = md5($password);
                        $password = $row["user_id"] . $password;
                        $password = hash("sha256", $password);
                        $row["user_pwd"] = $password;
                    }
                    $Result[$row["uno"]] = $row;
                }
                //$_SESSION["members"] = $members;
                //print_r($members);
            }    
        }
        return $Result;
    }

    function setSaveSession_UserMemberAll($bInit = false)
    {
        if(isset($_SESSION) && ($bInit == true || !isset($_SESSION["members"]) || !is_array($_SESSION["members"]) || !$_SESSION["members"] || !count($_SESSION["members"]) ) )
        {
            $members = $this->getUserMemberInfo(null);
            $_SESSION["members"] = $members;
        }
    }
    function setRemoveSession_UserMemberAll()
    {
        if(isset($_SESSION) && isset($_SESSION["members"]) )
        {
            unlink($_SESSION["members"]);
        }
    }
    function getUserMemberAll($bInit = false)
    {
        if(isset($_SESSION) && ($bInit == true || !isset($_SESSION["members"]) || !is_array($_SESSION["members"]) || !$_SESSION["members"] || !count($_SESSION["members"]) ) )
        {
            $this->setSaveSession_UserMemberAll(true);
        }
        return $_SESSION["members"];
    }
    function getUserDeptInfo($dept_no = null){
        global $userDB;

        $Result = null;
        if(isset($userDB) && is_object($userDB) && $userDB)
        {
            $_table_name = "[dbo].[BIZ_DEPT_SET_CTE]";
            $SQL = "SELECT * FROM {$_table_name} WHERE 1 = 1";
            if(isset($dept_no) && !is_array($dept_no) && $dept_no)
            {
                $SQL .= " AND dept_no = '{$dept_no}'";
            }
            $params = array();
            $userDB->query($SQL, $params);
            if($userDB->nf() > 0)
            {
                $Result = array();
                while($userDB->next_record()){
                    $row = $userDB->Record;
                    if(isset($row) && is_array($row) && array_key_exists("dept_no", $row) && isset($row["dept_no"]) && $row["dept_no"])
                    {
                        $Result[$row["dept_no"]] = $row;
                    }
                }
                //$_SESSION["members"] = $members;
                //print_r($members);
            }    
        }
        return $Result;
    }
}
$user = new User;

if((is_array($_POST) && array_key_exists("webbiz_user_id", $_POST) && $_POST["webbiz_user_id"] != "") //){
 || (is_array($_GET) && array_key_exists("webbiz_user_id", $_GET) && $_GET["webbiz_user_id"] != "")
 ){
    //unset($_SESSION["user"]);
    //Fun::print_r($_SESSION);
    //Fun::print_r($_POST);
    $userId = "";
    if (isset($_POST["webbiz_user_id"])) {
        $userId = $_POST["webbiz_user_id"];
    }
    else if (isset($_GET["webbiz_user_id"])) {
        $userId = $_GET["webbiz_user_id"];
    }
    $user->setWebBizLogin($userId,$msg);
} else {
    //Fun::print_r($_SESSION);
    //Fun::print_r($_POST);
}

// if($_POST["webbiz_user_id"] != ""){
//     $user->setWebBizLogin($_POST["webbiz_user_id"],$msg);
// }

//사용 제한 계정
$excludeUserList = array(
    1, 
    9208, 
    9414, 
    9473, 
    9474, 
    9643, 
    9644, 
    9692, 
    9645, 
    9646, 
    9647, 
    9648, 
    9649, 
    9650, 
    9651, 
    9652, 
    9653, 
    9654, 
    9655, 
    9656, 
    9657, 
    9658, 
    9659, 
    9660, 
    9661, 
    9662, 
    9663, 
    9664, 
    9665, 
    9666, 
    9667, 
    9668, 
    9669, 
    9674, 
    9692, 
    9716, 
    //9720, //erp
    9745,
    9746
);

//Fun::print_r($_SESSION);
?>