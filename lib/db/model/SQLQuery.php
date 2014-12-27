<?php

abstract class Db_Model_SQLQuery {
    protected $dbHandle;
    protected $result;
    protected $table;
    protected $primaryKey;

    public function connect($address, $account, $pwd, $name)
    {
        $this->dbHandle = new PDO('mysql:host=' . $address . '; dbname=' . $name .'; charset=utf8', $account, $pwd);
        $this->dbHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbHandle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }


    public function disconnect() {
        $this->dbHandle = null;
    }

    public function insert() {

        $fieldValues = '';
        foreach(explode(',',func_get_arg(0)) as $fieldValue) {
            $fieldValues = is_numeric($fieldValue) ? $fieldValues . $fieldValue . "," : $fieldValues . "'" . $fieldValue . "',";
        }
        $fieldValues = rtrim($fieldValues,',');

        $table_fields = $this->getColumnNames();

        foreach($table_fields as $key => $table_field) {
            if($table_field == $this->primaryKey) {
                unset($table_fields[$key]);
                break;
            }
        }
        //array_shift($table_fields);
        //unset($table_fields[$this->primaryKey]);
        $fields = implode(",",$table_fields );

        $query = "INSERT INTO `" . $this->table . "` (" . $fields . ") VALUES(" . $fieldValues . ")";
        $this->query($query);
    }

    public function update() {
        $table_fields = $this->getColumnNames();
        //array_shift($table_fields);
        //unset($table_fields[$this->primaryKey]);
        foreach($table_fields as $key => $table_field) {
            if($table_field == $this->primaryKey) {
                unset($table_fields[$key]);
                break;
            }
        }
        $fields = implode("=?, ",$table_fields );
        $fields = $fields . "=? ";

        # TODO: Change WHERE clause to handle different identifier names
        $query = "update " . $this->table . " set " . $fields . " where " . $this->primaryKey . "=?";
        $result = $this->dbHandle->prepare($query);
        $result->execute(explode(',',func_get_arg(0)));
    }



    public function selectAll() {
        $query = 'SELECT * FROM `'.$this->table.'`';
        return $this->query($query);
    }

    public function select($field, $value) {
        $query = 'SELECT * FROM `'.$this->table. '` WHERE `'. $field .'` = \''.$value.'\'';
        return $this->query($query, 1);
    }

    public function getNumRows() {
        return count($this->selectAll());
    }

    protected function query($query, $singleResult = 0) {

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

    protected function getColumnNames() {
        $query = $this->dbHandle->prepare("DESCRIBE " . $this->table);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function getPrimaryKeyName() {
        $query = $this->dbHandle->prepare("SHOW KEYS FROM " . $this->table .  " WHERE Key_name = 'PRIMARY'");
        $query->execute();
        $pk = $query->fetch(PDO::FETCH_ASSOC);
        return $pk['Column_name'];
    }


    protected function freeResult() {

    }

    protected function getError() {

    }


}