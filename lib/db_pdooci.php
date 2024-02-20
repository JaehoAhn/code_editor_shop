<?php

/*
 * Oracle/OCI8 accessor based on Session Management for PHP3
 *
 * (C) Copyright 1999-2000 Stefan Sels phplib@sels.com
 *
 * based on db_oracle.inc by Luis Francisco Gonzalez Hernandez
 * contains metadata() from db_oracle.inc 1.10
 *
 * $Id: db_oci8.inc,v 1.4 2000/07/12 18:22:34 kk Exp $
 *
 */

class DB_OCISql {
    var $Debug    =  0;
    var $sqoe     =  1; // sqoe= show query on error

    var $Database = "";
    var $User     = "";
    var $Password = "";

    var $Conn   = null;
    var $Record = array();
    var $Row = 0;
    var $Stmt;
    var $Error = "";
    var $RowCount;
    
    /* public: constructor */
    function DB_Sql($query = "") {
        $this->query($query);
    }

    function connect() {
        if ( is_null($this->Conn) ) {
            if($this->Debug) {
                printf("<br>Connecting to $this->Database...<br>\n");
            }
            try{
                $this->Conn=new PDO("oci:dbname=".$this->Database, $this->User, $this->Password);
                $this->Conn->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
                $this->Conn->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
                $this->Conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e){
                $this->halt($e->getMessage());
            }
            if($this->Debug) {
                printf("<br />Obtained the Conn: $this->Conn<br />\n");
            }
        }
    }

    function query($Query_String) {
        $stat = true;

        /* No empty queries, please, since PHP4 chokes on them. */
        if ($Query_String == "") {
            /* The empty query string is passed on from the constructor,
            * when calling the class without a query, e.g. in situations
            * like these: '$db = new DB_Sql_Subclass;'
            */
            return;
        }

        $this->connect();

        $this->Stmt=$this->Conn->prepare($Query_String);
        try {
            $this->Stmt->execute();
        }
        catch(PDOException $e){
            $stat = false;
            $this->Error=$this->Conn->errorInfo();
            //ORA-01403 : No data found
            if ($this->Error[1]!=1403 && $this->Error[1]!=0 && $this->sqoe) {
                echo "<BR><FONT color=red><B>".$this->Error[2]."<BR>Query :\"$Query_String\"</B></FONT>";
            }
        }

        $this->Row=0;

        if($this->Debug) {
            printf("Debug: query = %s<br>\n", $Query_String);
        }
        
        return $stat;
    }

    function next_record() {
        $row = $this->Stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row) {
            unset($this->Record);
            foreach($row as $key=>$val) {
                $this->Record[$key] = $val;
                if($this->Debug) {
                    echo"<b>[{$col}]</b>:".$row[$col]."<br>\n";
                }
            }
            $this->Row += 1;
            $stat=true;
        } 
        else {
            if ($this->Debug) {
                printf("<br>ID: %d,Rows: %d<br>\n",
                $this->Conn,$this->num_rows());
            }
            $this->Row+=1;

            $errInfo=$this->Conn->errorInfo();
            if(1403 == $errInfo[1]) { # 1043 means no more records found
                $this->Error="";
            } 
            else {
                $this->Error=$errInfo[2];
                if($this->Debug) {
                    printf("<br>Error: %s", $errInfo[2]);
                }
            }
            $stat=false;
        }
        return $stat;
    }

    function metadata($table,$full=false) {
        $res   = array();

    /*
     * Due to compatibility problems with Table we changed the behavior
     * of metadata();
     * depending on $full, metadata returns the following values:
     *
     * - full is false (default):
     * $result[]:
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags ("NOT NULL", "INDEX")
     *   [0]["format"] precision and scale of number (eg. "10,2") or empty
     *   [0]["index"]  name of index (if has one)
     *   [0]["chars"]  number of chars (if any char-type)
     *
     * - full is true
     * $result[]:
     *   ["num_fields"] number of metadata records
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags ("NOT NULL", "INDEX")
     *   [0]["format"] precision and scale of number (eg. "10,2") or empty
     *   [0]["index"]  name of index (if has one)
     *   [0]["chars"]  number of chars (if any char-type)
     *   ["meta"][field name]  index of field named "field name"
     *   The last one is used, if you have a field name, but no index.
     *   Test:  if (isset($result['meta']['myfield'])) {} ...
     */

        $this->connect();

        $SQL = "SELECT T.TABLE_NAME, T.COLUMN_NAME, T.DATA_TYPE, T.DATA_LENGTH, T.DATA_PRECISION, ".
            "T.DATA_SCALE, T.NULLABLE, T.CHAR_COL_DECL_LENGTH, I.INDEX_NAME ".
            "FROM ALL_TAB_COLUMNS T ".
            " LEFT OUTER JOIN ALL_IND_COLUMNS I ON T.COLUMN_NAME=I.COLUMN_NAME AND T.TABLE_NAME=I.TABLE_NAME ".
            "WHERE T.TABLE_NAME=UPPER('{$table}') ".
            "ORDER BY T.COLUMN_ID ";

        $i=0;
        foreach ($this->Conn->query($SQL) as $row) {
            $res[$i]["table"] =  $row["table_name"];
            $res[$i]["name"]  =  strtolower($row["column_name"]);
            $res[$i]["type"]  =  $row["data_type"];
            $res[$i]["len"]   =  $row["data_length"];
            if ($row["index_name"]) {
                $res[$i]["flags"] = "INDEX ";
            }
            $res[$i]["flags"] .= ($row["nullable"] == 'N')?'':'NOT NULL';
            $res[$i]["format"]='';
            if (!empty($row["data_precision"]) || !empty($row["data_scale"])) {
                $res[$i]["format"]= (int)$row["data_precision"].",".(int)$row["data_scale"];
            }
            $res[$i]["index"] =  $this->Record["index_name"];
            $res[$i]["chars"] =  $this->Record["char_col_decl_length"];
            if ($full) {
                $j=$res[$i]["name"];
                $res["meta"][$j] = $i;
                $res["meta"][strtoupper($j)] = $i;
            }
            $i++;
        }
        if ($full) {
            $res["num_fields"]=$i;
        }
        return $res;
    }

    function num_rows() {
        return $this->RowCount;
    }

    function nf() {
        return $this->num_rows();
    }

    function nextid($seqname) {
        $this->connect();

        $this->Stmt = $this->Conn->prepare("SELECT {$seqname}.NEXTVAL FROM DUAL");

        if(!$this->Stmt->execute()) {
            $this->Error=$this->Conn->errorInfo();
            if($this->Error[1]==2289) {
                $this->Stmt->closeCursor();
                $this->Stmt=$this->Conn->prepare("CREATE SEQUENCE {$seqname}");
                if(!$this->Stmt->execute()) {
                    $this->Error=$this->Conn->errorInfo();
                    $this->halt("<BR> nextid() function - unable to create sequence<br />".$this->Error[2]);
                }
                else {
                    $this->Stmt->closeCursor();
                    $this->Stmt = $this->Conn->prepare("SELECT {$seqname}.NEXTVAL FROM DUAL");
                    $this->Stmt->execute();
                }
            }
        }

        if ($row = $this->Stmt->fetch(PDO::FETCH_ASSOC)) {
            $next_id = $row["nextval"];
        } 
        else {
            $next_id = 0;
        }
        $this->Stmt->closeCursor();
        return $next_id;
    }

    function disconnect() {
        if($this->Debug) {
            printf("DisConnecting...<br>\n");
        }
        $this->Stmt->closeCursor();
        $this->Conn = null;
    }

    function halt($msg) {
        printf("<b>Database error:</b> %s<br>\n", $msg);
        printf("<b>ORACLE Error</b>: %s<br>\n", $this->Error[2]);
        // $this->Conn->rollBack();
        die("Session halted.");
    }

    function unsetAutoCommit() {
        $this->connect();
        $this->Conn->setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
    }

    function beginTransaction() {
        $this->connect();
        $this->Conn->beginTransaction();
    }

    function doCommit() {
        $this->connect();
        $this->Conn->commit();
        $this->Conn->setAttribute(PDO::ATTR_AUTOCOMMIT, TRUE);
    }
	function Commit() {
        $this->doCommit();
    }
    function doRollBack() {
        $this->connect();
        $this->Conn->rollBack();
    }
	function RollBack() {
        $this->doRollBack();
    }
	function inTransaction(){
		if($this->Conn)
		{
			return $this->Conn->inTransaction();
		}
		return false;
	}
	function endTransaction() {
		if($this->inTransaction())
		{
			$this->doRollBack();
		}
    }

    function table_names() {
        $this->connect();
        $this->query("SELECT table_name,tablespace_name FROM user_tables");
        $i=0;
        while ($this->next_record()) {
            $info[$i]["table_name"]     =$this->Record["table_name"];
            $info[$i]["tablespace_name"]=$this->Record["tablespace_name"];
            $i++;
        }
        return $info;
    }
	/**
	 * 현재 레코드의 컬럼을 이용한 VALUE 가져오기
	 * @$Name : 컬럼명
	 * @return : Value
	 */
	function f($Name) {
		if (is_object($this->Record[$Name])) {
			return $this->Record[$Name]->load();
		} else {
			return $this->Record[$Name];
		}
	}
}
?>
