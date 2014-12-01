<?php

class Core_Model_SQLQuery {
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

        //$this->result = $this->dbHandle->query($query);
        $this->result = $this->dbHandle->prepare($query);
        $this->result->execute();
        $result = $this->result->fetchAll(PDO::FETCH_ASSOC);

//        if (preg_match("/select/i",$query)) {
//            $result = array();
//            $table = array();
//            $field = array();
//            $tempResults = array();
//            $numOfFields = $this->result->rowCount();
//            for ($i = 0; $i < $numOfFields; ++$i) {
//                array_push($table,mysqli_field_table($this->result, $i));
//                array_push($field,mysqli_field_name($this->result, $i));
//            }
//
//
//            while ($row = $this->result->fetch()) {
//                for ($i = 0;$i < $numOfFields; ++$i) {
//                    $table[$i] = trim(ucfirst($table[$i]),"s");
//                    $tempResults[$table[$i]][$field[$i]] = $row[$i];
//                }
//                if ($singleResult == 1) {
//                    //mysqli_free_result($this->result);
//                    return $tempResults;
//                }
//                array_push($result,$tempResults);
//            }
//            //mysqli_free_result($this->result);
            return($result);
//        }


    }

    function getNumRows() {
        return mysqli_num_rows($this->result);
    }

    function freeResult() {
        mysqli_free_result($this->result);
    }

    function getError() {
        return mysqli_error($this->dbHandle);
    }


}