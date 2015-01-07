<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/6/15
 * Time: 11:17 AM
 */

class Db_Model_EAV extends Core_model_Singleton {
    private $database;
    private $collections = array();

    protected function _init() {
        $className = get_class($this);
        $tableName = strtolower(end(preg_split('#_Model_#', $className , null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY)));
        $this->database = App::getModel('db/SQLQuery');
        $this->database->setTableName($tableName . 's');
        $this->database->tabelInit();
        $this->database->setPrimary();
    }

    public function load($id) {
        $result = $this->database->select();

    }


}