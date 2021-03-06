<?php

abstract class Db_Model_SQLQuery extends Core_Model_Singleton
{
    protected $dbHandle;
    protected $result;
    protected $table;
    protected $primaryKey;
    protected $scriptFileName;
    protected $duplicatesAllowed;
    protected $optionalFields = array();

    public function insert()
    {
        $fieldValues = '';
        foreach (explode(',', func_get_arg(0)) as $fieldValue) {
            $fieldValues = is_numeric($fieldValue) ? $fieldValues . $fieldValue . "," : $fieldValues . "'" . $fieldValue . "',";
        }
        $fieldValues = rtrim($fieldValues, ',');

        $table_fields = $this->getColumnNames();

        foreach ($table_fields as $key => $table_field) {
            if ($table_field == $this->primaryKey) {
                unset($table_fields[$key]);
            } else if(array_key_exists($table_field,$this->optionalFields)) {
                unset($table_fields[$key]);
            }
        }
        $fields = implode(",", $table_fields);

        $query = "INSERT INTO `" . $this->table . "` (" . $fields . ") VALUES(" . $fieldValues . ")";
        $this->query($query);
    }

    public function update()
    {
        $table_fields = $this->getColumnNames();

        # Loop through the array and remove the element containing the primary key name
        foreach ($table_fields as $key => $table_field) {
            if ($table_field == $this->primaryKey) {
                unset($table_fields[$key]);
                break;
            }
        }

        $fields = implode("=?, ", $table_fields);
        $fields = $fields . "=? ";
        $query = "update " . $this->table . " set " . $fields . " where " . $this->primaryKey . "=?";
        $result = $this->dbHandle->prepare($query);
        $result->execute(explode(',', func_get_arg(0)));
    }

    public function remove($id)
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . '=' . $id;
        $this->query($query);
    }


    public function selectAll()
    {
        $query = 'SELECT * FROM `' . $this->table . '`';
        return $this->query($query);
    }

    public function select($field, $value)
    {
        $query = 'SELECT * FROM `' . $this->table . '` WHERE `' . $field . '` = \'' . $value . '\'';
        return $this->query($query, 1);
    }

    public function getNumRows()
    {
        return count($this->selectAll());
    }

    public function setTableName($name = false)
    {
        if ($name) {
            $this->table = $name;
        }
    }

    public function setPrimaryKey($name = false)
    {
        if ($name) {
            $this->primaryKey = $this->getPrimaryKeyName();
        }
    }

    protected function query($query, $singleResult = 0)
    {

        try {
            # SELECT statement
            if (preg_match("/select/i", $query)) {
                $this->result = $this->dbHandle->prepare($query);
                $this->result->execute();
                $result = $this->result->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $affected_rows = $this->dbHandle->exec($query);
                return ($affected_rows);
            }
            return ($result);

        } catch (Exception $e) {
            //Do nothing
        }
    }

    protected function getColumnNames()
    {
        $query = $this->dbHandle->prepare("DESCRIBE " . $this->table);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function getPrimaryKeyName()
    {
        $query = $this->dbHandle->prepare("SHOW KEYS FROM " . $this->table . " WHERE Key_name = 'PRIMARY'");
        $query->execute();
        $pk = $query->fetch(PDO::FETCH_ASSOC);
        return $pk['Column_name'];
    }

    protected function getForeignKeyAssoc($fieldName)
    {
        $stmt = "SELECT i.TABLE_NAME, i.CONSTRAINT_TYPE, i.CONSTRAINT_NAME, k.REFERENCED_TABLE_NAME, k.REFERENCED_COLUMN_NAME
            FROM information_schema.TABLE_CONSTRAINTS i
            LEFT JOIN information_schema.KEY_COLUMN_USAGE k ON i.CONSTRAINT_NAME = k.CONSTRAINT_NAME
            WHERE i.CONSTRAINT_TYPE = 'FOREIGN KEY'
            AND i.TABLE_SCHEMA = DATABASE()
            AND i.TABLE_NAME = '" . $this->table . "'" . "AND k.REFERENCED_COLUMN_NAME = '" . $fieldName . "'";

        $query = $this->dbHandle->prepare($stmt);
        $query->execute();
        $pk = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($pk)) {
            return $pk;
        }

        return false;
    }

    protected function validColumnName($name)
    {
        $fields = $this->getColumnNames();
        foreach ($fields as $field) {
            if ($name == $field) {
                return true;
            }
        }
        return false;
    }

    protected function freeResult()
    {
    }

    protected function getError()
    {
    }

    protected function tableInit()
    {
        if (!$this->tableExists()) {
            if (isset($this->scriptFileName)) {
                $query = file_get_contents(ROOT . 'assets' . DS . 'sql' . DS . strtolower($this->scriptFileName));
            } else {
                $query = file_get_contents(ROOT . 'assets' . DS . 'sql' . DS . $this->table . '.sql');
            }
            $results = $this->dbHandle->prepare($query);
            $results->execute();
        }
    }

    protected function tableExists()
    {
        try {
            $results = $this->dbHandle->query('SELECT 1 FROM ' . $this->table);
            if (count($results) > 0) {
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    protected function isRequired($name)
    {
        $module = App::getHelper()->getModule();
        $db = App::getHelper()->getDbCredentials($module);
        $stmt = "SELECT TABLE_CATALOG AS Database_Name, TABLE_SCHEMA, TABLE_NAME, COLUMN_NAME, IS_NULLABLE FROM INFORMATION_SCHEMA . COLUMNS WHERE TABLE_SCHEMA = '" . $db['name'] . "' AND TABLE_NAME = '" . $this->table ."'AND IS_NULLABLE = 'NO'";
        $results = $this->query($stmt);

        foreach($results as $row) {
            if($row['COLUMN_NAME'] == $name) {
                return true;
            }
        }
        return false;
    }

    protected function enableDuplicates() {
        $this->duplicatesAllowed = true;
    }

    protected function disableDuplicates() {
        $this->duplicatesAllowed = false;
    }


}