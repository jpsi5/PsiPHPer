<?php

class Db_Model_SQLQuery {
    protected $dbHandle;
    protected $result;
    protected $table;

    function connect($address, $account, $pwd, $name)
    {
        $this->dbHandle = new PDO('mysql:host=' . DB_HOST . '; dbname=' . DB_NAME .'; charset=utf8', DB_USER, DB_PASSWORD);
        $this->dbHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbHandle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }


    function disconnect() {
    }

    function selectAll() {
        $query = 'select * from `'.$this->table.'`';
        return $this->query($query);
    }

    function select($id) {
        $query = 'select * from `'.$this->table.'` where `id` = \''.$id.'\'';
        return $this->query($query, 1);
    }

    function query($query, $singleResult = 0) {

        # SELECT statement
        if(preg_match("/select/i",$query)) {
            $this->result = $this->dbHandle->prepare($query);
            $this->result->execute();
            $result = $this->result->fetchAll(PDO::FETCH_ASSOC);
        }
        else {
            $affected_rows = $this->dbHandle->exec($query);
            return ($affected_rows);
        }
        return($result);
    }

    function getNumRows() {
        return count($this->selectAll());
    }

    function freeResult() {
        mysqli_free_result($this->result);
    }

    function getError() {
        return mysqli_error($this->dbHandle);
    }


}