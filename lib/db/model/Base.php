<?php

class Db_Model_Base extends Db_Model_SQLQuery {

    private $model;
    protected static $_instance;

    # Returns the singleton instance of this class
    public static function getInstance(){
        if(is_null(static::$_instance)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    public function load() {}

    public function save() {}

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    private function __construct() {

        $className = get_class($this);
        $temp = explode('_',$className);
        $this->model = end($temp);
        $this->table = strtolower($this->model) . 's';

        # Use config.xml in order access database
        $module = $temp[0];
        $db = App::getHelper('core/base')->getDbCredentials($module);
        $this->connect($db["host"],$db["user"],$db["password"],$db["name"]);
    }
}