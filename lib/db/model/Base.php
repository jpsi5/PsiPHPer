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

    public function set($arg) {
        $this->model = $arg;
    }

    public function get() {
        return $this->model;
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