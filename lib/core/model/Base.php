<?php

abstract class Core_Model_Base extends Core_Model_SQLQuery {

    protected $model;

    public function __construct() {
        $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $className = get_class($this);
        $temp = explode('_',$className);
        $this->model = end($temp);
        $this->table = strtolower($this->model) . 's';
    }
}