<?php

abstract class Db_Model_SQLQuery extends Core_Model_Singleton {
    protected $dbHandle;
    protected $result;
    protected $table;
    protected $primaryKey;
    protected $scriptFileName;

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
        $fields = implode(",",$table_fields );

        $query = "INSERT INTO `" . $this->table . "` (" . $fields . ") VALUES(" . $fieldValues . ")";
        $this->query($query);
    }

    public function update() {
        $table_fields = $this->getColumnNames();

        # Loop through the array and remove the element containing the primary key name
        foreach($table_fields as $key => $table_field) {
            if($table_field == $this->primaryKey) {
                unset($table_fields[$key]);
                break;
            }
        }

        $fields = implode("=?, ",$table_fields );
        $fields = $fields . "=? ";
        $query = "update " . $this->table . " set " . $fields . " where " . $this->primaryKey . "=?";
        $result = $this->dbHandle->prepare($query);
        $result->execute(explode(',',func_get_arg(0)));
    }

    protected function remove($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . '=' .$id;
        $this->query($query);
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

    protected function validColumnName($name) {
        $fields = $this->getColumnNames();
        foreach($fields as $field) {
            if($name == $field) {
                return true;
            }
        }
        return false;
    }


    protected function freeResult() {}

    protected function getError() {}

    protected function tableInit() {
        if(!$this->tableExists()) {
            if(isset($this->scriptFileName)) {
                $query = file_get_contents(ROOT . 'assets' . DS . 'sql' . DS . strtolower($this->scriptFileName));
            }
            else {
                $query = file_get_contents(ROOT . 'assets' . DS . 'sql' . DS . $this->table . '.sql');
            }
            $results = $this->dbHandle->prepare($query);
            $results->execute();
        }
    }

    protected function tableExists() {
        try {
            $results = $this->dbHandle->query('SELECT 1 FROM ' . $this->table);
            if(count($results) > 0) {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }


}