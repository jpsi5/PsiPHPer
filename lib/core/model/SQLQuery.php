<?php

class Core_Model_SQLQuery {
    protected $dbHandle;
    protected $result;
    protected $table;

    function connect($address, $account, $pwd, $name)
    {

        $this->dbHandle = new mysqli($address, $account, $pwd);
        if ($this->dbHandle != 0) {
            if (mysqli_select_db($name, $this->dbHandle)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }


    function disconnect() {
        if (mysqli_close($this->dbHandle) != 0) {
            return 1;

        }
        else {
            return 0;
        }
    }

    function selectAll() {
        $query = 'select * from `'.$this->table.'`';
        return $this->query($query);
    }

    function select($id) {
        $query = 'select * from `'.$this->table.'` where `id` = \''.mysql_real_escape_string($id).'\'';
        return $this->query($query, 1);
    }

    function query($query, $singleResult = 0) {

        $this->result = mysqli_query($query, $this->dbHandle);

        if (preg_match("/select/i",$query)) {
            $result = array();
            $table = array();
            $field = array();
            $tempResults = array();
            $numOfFields = mysqli_num_fields($this->result);
            for ($i = 0; $i < $numOfFields; ++$i) {
                array_push($table,mysqli_field_table($this->result, $i));
                array_push($field,mysqli_field_name($this->result, $i));
            }


            while ($row = mysqli_fetch_row($this->result)) {
                for ($i = 0;$i < $numOfFields; ++$i) {
                    $table[$i] = trim(ucfirst($table[$i]),"s");
                    $tempResults[$table[$i]][$field[$i]] = $row[$i];
                }
                if ($singleResult == 1) {
                    mysqli_free_result($this->result);
                    return $tempResults;
                }
                array_push($result,$tempResults);
            }
            mysqli_free_result($this->result);
            return($result);
        }


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