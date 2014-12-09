<?php

class Db_Model_Base extends Db_Model_SQLQuery {

    private $model;
    private static $instance;

    # Returns the singleton instance of this class
    public static function getInstance(){
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __get() {}

    public function __set() {}

    private function __construct() {

        $className = get_class($this);
        $temp = explode('_',$className);
        $this->model = end($temp);
        $this->table = strtolower($this->model) . 's';

        # Use config.xml in order access database
        $module = $temp[0];
        $helper = App::getHelper('core/base');
        $db = $helper->getDbCredentials($module);
        $this->connect($db["host"],$db["user"],$db["password"],$db["name"]);
    }
}