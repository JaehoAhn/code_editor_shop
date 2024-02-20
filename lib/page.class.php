<?php
class Page{
    var $user;
//     var $Fun;
//     var $tpl;
    /**
     * PHP5용 Class 생성자
     */
    function __construct(){
        $this->Page();
    }
    function __destruct(){
      //echo "__destruct";
    }
    /**
     * Class 생성자
     */
    function Page() {
//         global $Fun;
        global $user;
//         global $tpl;
//         if(!get_class($Fun)){
//             $this->Fun = new Fun;
//             $Fun = &$this->Fun;
//         } else {
//             $this->Fun = &$Fun;
//         }
//         if(!get_class($User)){
        if(!isset($user)){
                $this->user = new User;
            $user = &$this->user;
        } else {
            $this->user = &$user;
        }
//         if(!get_class($tpl)){
//             $this->tpl = new Template;
//             $tpl = $this->tpl;
//         } else {
//             $this->tpl = $tpl;
//         }
    }
//     function logout(){
//         $this->pageLogout();
//     }
//     function pageLogout(){
//         unset($_SESSION["user"]);
//     }
//     function pageLogin($args = NULL){
//         if(!$this->user->uno){
//             //$this->Fun->alert("접속 권한이 없습니다.");
//             if($_POST["login_mode"] == "login" && $_POST["login_user_id"] && $_POST["login_password"]){
//                 if(!$this->user->setLogin($_POST["login_user_id"], $_POST["login_password"], $login_message)){
//                     $this->Fun->msg($login_message);
//                     include dirname(__FILE__) . "/login_form.php";
//                     exit;
//                 } else {
//                     if($_SERVER["REMOTE_ADDR"] == "61.41.17.41" || $_SERVER["REMOTE_ADDR"] == "61.41.17.48"){
//                         Fun::print_r($_POST);
//                     }
//                     if(!$_POST["login_save_id"]){
//                         echo "<img src='/lib/save_userid.php?user_id=null' border=0 width=0 height=0 />";
//                         //echo "dddd";
//                         //exit;
//                     } else {
//                         echo "<img src='/lib/save_userid.php?user_id=" . $_POST["login_user_id"] . "' border=0 width=0 height=0 />";
//                     }
//                     $this->Fun->goPage($this->Fun->getExpUrl("login_mode,login_user_id,login_password", ""));
//                 }
//             } else {
//                 include dirname(__FILE__) . "/login_form.php";
//                 exit;
//             }
//         }
//         if(!$this->user->uno){
//             $this->Fun->alert("접속 권한이 없습니다.");
//             return false;
//         }
//         $args = func_get_args();
//         if ($args){
//             $this->getPageAuth($args);
//         }
//     }
//     function getPageAuth($args){
//         $flag = true;
//         if(!$args){
//             $args = func_get_args();
//         }
//         if(count($args) > 0){
//             $flag = false;
//             foreach($args as $_key => $_val){
//                 $_val = strtoupper($_val);
//                 if($_SESSION["user"][$_val] == "Y"){
//                     //echo $_val;
//                     $flag = true;
//                 }
//             }
//         }
//         if($_SESSION["user"]["AT"] == "Y"){
//             $flag = true;
//         }
//         if($flag == false){
//             $this->Fun->alert("접속 권한이 없습니다.");
//         }
//     }
    /**
     * 순수하게 PM권한만 가지고 있을경우 true 반환
     */
    function isAuthOnlyPM(){
        $flag = false;
//         if($_SESSION["user"]["PM"] == "Y"){
//             $flag = true;
//         }
//         if($_SESSION["user"]["AT"] == "Y"){
//             $flag = false;
//         }
//         if($_SESSION["user"]["DT"] == "Y"){
//             $flag = false;
//         }
//         if($_SESSION["user"]["MT"] == "Y"){
//             $flag = false;
//         }
        if(($_SESSION["user"]["PM"] == "Y") 
            && ($_SESSION["user"]["AT"] !== "Y") 
            && ($_SESSION["user"]["DT"] !== "Y")
            && ($_SESSION["user"]["MT"] !== "Y")
            && ($_SESSION["user"]["TM"] !== "Y")
		) {
            $flag = true;
        }

        return $flag;
    }
    /**
     * 관리자인지 여부
     */
    function isAT(){
        return $this->isAdmin();
    }
    function isAdmin(){
        if($_SESSION["user"]["AT"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 임원진인지 여부
     */
    function isDT(){
        return $this->isDirectorManager();
    }
    function isDirectorManager(){
        if($_SESSION["user"]["DT"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 팀장인지 여부
     */
    function isTM(){
        return $this->isTeamManager();
    }
    function isTeamManager(){
        if($_SESSION["user"]["TM"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 프로젝트 등록/수정 권한인지 여부
     */
    function isMT(){
        return $this->isManager();
    }
    function isManager(){
        if($_SESSION["user"]["MT"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 인사명령 입력자인지 여부
     */
    function isPA(){
        return $this->isPersonnelOrderAdmin();
    }
    function isPersonnelOrderAdmin(){
        if($_SESSION["user"]["PA"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 타임시트 관리자인지 여부
     */
//     function isTA(){
//         return $this->isTimeAdmin();
//     }
//     function isTimeAdmin(){
//         if($_SESSION["user"]["TA"] == "Y"){
//             return true;
//         } else {
//             return false;
//         }
//     }
    /**
     * 공문 관리자인지 여부
     */
    function isDM(){
        return $this->isDocumentManager();
    }
    function isDocumentManager(){
        if($_SESSION["user"]["DM"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 공문 작성자인지 여부
     */
    function isDW(){
        return $this->isDocumentWriter();
    }
    function isDocumentWriter(){
        if($_SESSION["user"]["DW"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 개발팀인지 여부
     */
    function isIT(){
        return $this->isInfomationTechnolgy();
    }
    function isInfomationTechnolgy(){
        if($_SESSION["user"]["IT"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 주간업무보고 편집자인지 여부
     */
    function isWR(){
        return $this->isWeeklyReport();
    }
    function isWeeklyReport(){
        if($_SESSION["user"]["WR"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 주간로드율 편집자인지 여부
     */
    function isWS(){
        return $this->isWeeklySummary();
    }
    function isWeeklySummary(){
        if($_SESSION["user"]["WS"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * PM인지 여부
     */
    function isPM(){
        return $this->isProjectManager();
    }
    function isProjectManager(){
        if($_SESSION["user"]["PM"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 정보책임자인지 여부
     */
    function isIM(){
        return $this->isInfomationManager();
    }
    function isInfomationManager(){
        if($_SESSION["user"]["IM"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * LL 관리자인지 여부
     */
    function isLL(){
        return $this->isLessonAndLearnManager();
    }
    function isLessonAndLearnManager(){
        if($_SESSION["user"]["LL"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
    /**
     * 임직원 역량강화 관리자인지 여부
     */
    function isEA(){
        return $this->isExamAdministrator();
    }
    function isExamAdministrator(){
        if($_SESSION["user"]["EA"] == "Y"){
            return true;
        } else {
            return false;
        }
    }
}
$page = new Page;
?>