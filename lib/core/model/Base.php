<?php

class Core_Model_Base extends Core_Model_SQLQuery {

    private $model;
    private static $instance;

    # Returns the singleton instance of this class
    public static function getInstance(){
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {

        # TODO: Change the connect method to use config.xml in order access database
        $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $className = get_class($this);
        $temp = explode('_',$className);
        $this->model = end($temp);
        $this->table = strtolower($this->model) . 's';
    }
}