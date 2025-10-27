<?php

//================================================================================================================================
// MySQL

class MySQLManager {

    private $_dbh = null;

    private function ConnectDB() {
        if ($this->_dbh != null) {
            return $this->_dbh;
        }

        // Connect Master DB
        $serverAddr = Config::$DB_ADDR;
        $uid = Config::$DB_ID;
        $pwd = Config::$DB_PW;
        $dbname = Config::$DB_NAME;
        try {
            $this->_dbh = new PDO("mysql:host=$serverAddr;dbname=$dbname", $uid, $pwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'", PDO::ATTR_PERSISTENT => false));
        } catch (PDOException $err) {
            //Log::File("DB Connect Error : " . $err->getMessage(), 'mysql');
            print("DB Connect Error");
            return null;
        }
        return $this->_dbh;
    }

    public function QuerySafe($statement, $args = null) {
        $dbh = $this->ConnectDB();
        if ($dbh == null) {
            //Log::File(sprintf("FAILED QuerySafe : %s(args : %s)", $statement, var_export($args, true)), 'mysql');
            print("DB Query Error");
            return false;
        }

        $sth = $dbh->prepare($statement);
        try {
            $sth->execute($args);
        } catch (PDOException $err) {
            return $err;
        }
        return $sth;
    }

    public function GetRowCount($stmt) {
        if ($stmt == false)
            return null;
        return $stmt->rowCount();
    }

    public function GetNextRowData($stmt) {
        if ($stmt == false)
            return null;
        return $stmt->fetch();
    }

    public function GetFieldData($rowdata, $key) {
        return GetVal($rowdata, $key);
    }

    public function GetAllData($stmt) {
        if ($stmt == false)
            return null;
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function IsOK($stmt) {
        if ($stmt == false)
            return false;
        return $stmt->errorCode() == '00000';
    }

    public function GetErrorCode($stmt) {
        if ($stmt == false)
            return false;
        return $stmt->errorCode();
    }
    
    public function Disconnect() {
        $this->_dbh = null;
    }
}

?>