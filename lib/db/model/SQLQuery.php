<?php

abstract class Db_Model_SQLQuery {
    protected $dbHandle;
    protected $result;
    protected $table;

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
        foreach(func_get_args() as $fieldValue) {
            $fieldValues = is_numeric($fieldValue) ? $fieldValues . $fieldValue . "," : $fieldValues . "'" . $fieldValue . "',";
        }
        $fieldValues = rtrim($fieldValues,',');

        $q = $this->dbHandle->prepare("DESCRIBE " . $this->table);
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
        array_shift($table_fields);
        $fields = implode(",",$table_fields );

        $query = "insert into `" . $this->table . "` (" . $fields . ") values(" . $fieldValues . ")";
        $this->query($query);
    }

    public function update() {
        $q = $this->dbHandle->prepare("DESCRIBE " . $this->table);
        $q->execute();
        $table_fields = $q->fetchAll(PDO::FETCH_COLUMN);
        array_shift($table_fields);
        $fields = implode("=?, ",$table_fields );
        $fields = $fields . "=? ";

        # TODO: Change WHERE clause to handle different identifier names
        $query = "update " . $this->table . " set " . $fields . " where id=?";
        $result = $this->dbHandle->prepare($query);
        $result->execute(func_get_args());
    }



    public function selectAll() {
        $query = 'select * from `'.$this->table.'`';
        return $this->query($query);
    }

    public function select($field, $value) {
        $query = 'select * from `'.$this->table. '` where `'. $field .'` = \''.$value.'\'';
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



    protected function freeResult() {

    }

    protected function getError() {

    }


}