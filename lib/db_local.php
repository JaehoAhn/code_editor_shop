<?php
//웹비즈 DB
class DB_OCISql_local extends DB_OCISql {
    var $Host     = "";
    var $Database = "";
    var $User     = "";
    var $Password = "";
    var $RecordAll= array();
    var $isNumRow = false;

    function query($Query_String, $params = array()) {
        $stat = true;
        unset($this->RecordAll);
        $this->isNumRow = false;

        $Query_String = trim($Query_String);
        if ($Query_String == "") {
            return;
        }

        $this->connect();

        try {
            $this->Stmt=$this->Conn->prepare(iconv("UTF-8", "EUC-KR//TRANSLIT", $Query_String));
            if(!$this->Stmt) {
                $this->Error=$this->Conn->errorInfo();
            } 
            else {
                array_walk($params, 'convertUTF8toEUCKR');
                foreach (array_keys($params) as $key) {
                    // oci_bind_by_name($stid, $key, $val) does not work
                    // because it binds each placeholder to the same location: $val
                    // instead use the actual location of the data: $ba[$key]
                    $this->Stmt->bindValue($key, $params[$key]);
                }
                $this->Stmt->execute();
            }
        }
        catch(PDOException $e){
            $this->Error=$this->Conn->errorInfo();
            //ORA-01403 : No data found
            if ($this->Error[1]!=1403 && $this->Error[1]!=0 && $this->sqoe) {
                echo "<BR><FONT color=red><B>".$this->Error[2]."<BR>Query :\"$Query_String\"</B></FONT>";
            }
            $stat = false;
        }

        $this->Row=0;

        if($this->Debug) {
            printf("Debug: query = %s<br>\n", iconv("UTF-8", "EUC-KR", $Query_String));
        }

        if (strtoupper(substr($Query_String, 0, 1)) == "S" || strtoupper(substr($Query_String, 0, 1)) == "W"){
            $this->isNumRow = true;
            $this->RecordAll = $this->Stmt->fetchAll();
            $this->RowCount = count($this->RecordAll);
        }
        else {
            $this->isNumRow = false;
        }
        return $stat;
    }

    function next_record() {
        unset($this->Record);
        if ($this->isNumRow){
            if ($this->RowCount > $this->Row){
                $row = $this->RecordAll[$this->Row];
                foreach($row as $key=>$val) {
                    $this->Record[$key] = iconv("EUC-KR", "UTF-8//TRANSLIT", $val);
                    if($this->Debug) {
                        echo"<b>[{$key}]</b>:".$this->Record[$key]."<br>\n";
                    }
                }
                $stat = true;
            } 
            else {
                if ($this->Debug) {
                    printf("<br>ID: %d,Rows: %d<br>\n", $this->Conn,$this->num_rows());
                }
                $errInfo=$this->Conn->errorInfo();
                if(1403 == $errInfo[1]) { # 1043 means no more records found
                    $this->Error="";
                } 
                else {
                    if($this->Debug) {
                        printf("<br>Error: %s", $errInfo[2]);
                    }
                    $this->Error=$errInfo[2];
                }
                $stat=false;
            }
            $this->Row += 1;
        } 

        return $stat;
    }
}

//Timesheet DB
class DB extends DB_OCISql_local {
    var $Host     = _DB_Host_Oracle_;
    var $Database = _DB_Name_Oracle_;
    var $User     = _DB_User_Oracle_;
    var $Password = _DB_Pass_Oracle_;
    var $RecordAll= array();
    var $RowCount;
    var $isNumRow = false;

    function query($Query_String, $params = array()) {
        $stat = false;
        unset($this->RecordAll);
        $this->isNumRow = false;

        $Query_String = trim($Query_String);
        if ($Query_String == "") {
            return $stat;
        }

        $this->connect();

        try {
            $this->Stmt=$this->Conn->prepare(iconv("UTF-8", "EUC-KR//TRANSLIT", $Query_String));
            array_walk($params, 'convertUTF8toEUCKR');
            foreach ($params as $key => $val) {
                $this->Stmt->bindValue($key, $val);
            }
            $result=$this->Stmt->execute();
        }
        catch(PDOException $e){
            $this->Error=$this->Conn->errorInfo();
            //ORA-01403 : No data found
            if ($this->Error[1]!=1403 && $this->Error[1]!=0 && $this->sqoe) {
                echo "<BR><FONT color=red><B>".$this->Error[2]."<BR>Query :\"$Query_String\"</B></FONT>";
            }
            $stat = false;
        }

        $this->Row=0;

        if($this->Debug) {
            printf("Debug: query = %s<br>\n", iconv("EUC-KR", "UTF-8//TRANSLIT", $Query_String));
        }

        if (strtoupper(substr($Query_String, 0, 1)) == "S" || strtoupper(substr($Query_String, 0, 1)) == "W"){
            $this->isNumRow = true;
            $this->RecordAll = $this->Stmt->fetchAll();
            $this->RowCount = count($this->RecordAll);
        }
        else {
            $this->isNumRow = false;
        }
        $stat = true;

        return $stat;
    }

    function query_lob($Query_String, $params = array(), $columns = array()) {
        $stat = false;
        unset($this->RecordAll);
        $this->isNumRow = false;
        
        $Query_String = trim($Query_String);
        if ($Query_String == "") {
            return $stat;
        }

        $this->connect();

        try {
            $this->Stmt=$this->Conn->prepare(iconv("UTF-8", "EUC-KR//TRANSLIT", $Query_String));
            foreach ($params as $param) {
                if ($param[2]) {
                    $this->Stmt->bindParam($param[0], iconv("UTF-8", "EUC-KR//TRANSLIT", $param[1]), PDO::PARAM_STR, strlen($param[1]));
                }
                else {
                    $this->Stmt->bindParam($param[0], iconv("UTF-8", "EUC-KR//TRANSLIT", $param[1]));
                }
            }
            $result=$this->Stmt->execute();
        }
        catch(PDOException $e){
            $this->Error=$this->Conn->errorInfo();
            //ORA-01403 : No data found
            if ($this->Error[1]!=1403 && $this->Error[1]!=0 && $this->sqoe) {
                echo "<BR><FONT color=red><B>".$this->Error[2]."<BR>Query :\"$Query_String\"</B></FONT>";
            }
            $stat = false;
        }

        $this->Row=0;

        if($this->Debug) {
            printf("Debug: query = %s<br>\n", iconv("EUC-KR", "UTF-8//TRANSLIT", $Query_String));
        }

        if (strtoupper(substr($Query_String, 0, 1)) == "S" || strtoupper(substr($Query_String, 0, 1)) == "W"){
            $this->isNumRow = true;
            $values = array();
            foreach ($columns as $key => $column) {
                if ($column[1]) {
                    $this->Stmt->bindColumn($column[0], $values[$key], PDO::PARAM_LOB);
                }
                else {
                    $this->Stmt->bindColumn($column[0], $values[$key]);
                }
            }
            while($this->Stmt->fetch(PDO::FETCH_BOUND)) {
                $row = array();
                foreach($columns as $key => $column) {
                    if ($column[1]) {
                        $row[$column[0]] = stream_get_contents($values[$key]);
                    }
                    else {
                        $row[$column[0]] = $values[$key];
                    }
                }
                $this->RecordAll[] = $row;
            }
            $this->RowCount = count($this->RecordAll);
        }
        else {
            $this->isNumRow = false;
        }
        $stat = true;

        return $stat;
    }

    function next_record() {
        unset($this->Record);
        if ($this->isNumRow){
            if ($this->RowCount > $this->Row){
                $row = $this->RecordAll[$this->Row];
                foreach($row as $key=>$val) {
                    if (!is_null($val)) {
                        if (is_resource($val)) {
                            $val = stream_get_contents($val);
                        }
                        else if (!is_string($val)) {
                            $val = settype($val, "string");
                        }
                        $this->Record[$key] = iconv("EUC-KR", "UTF-8//TRANSLIT", $val);

                        if($this->Debug) {
                            echo"<b>[{$key}]</b>:".$this->Record[$key]."<br>\n";
                        }
                    }
                }
                $stat = true;
            } 
            else {
                if ($this->Debug) {
                    printf("<br>ID: %d,Rows: %d<br>\n", $this->Conn,$this->num_rows());
                }
                $errInfo=$this->Conn->errorInfo();
                if(1403 == $errInfo[1]) { # 1043 means no more records found
                    $this->Error="";
                } 
                else {
                    if($this->Debug) {
                        printf("<br>Error: %s", $errInfo[2]);
                    }
                    $this->Error=$errInfo[2];
                }
                $stat=false;
            }
            $this->Row += 1;
        } 

        return $stat;
    }
}

function convertUTF8toEUCKR(&$item, $key) {
	$item = iconv("UTF-8", "EUC-KR//TRANSLIT", $item);
}

?>