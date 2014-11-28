<?php

class Core_Model_Base extends Core_Model_SQLQuery {

    protected $model;

    public function __construct() {
        $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $temp = get_class($this);
        $temp2 = explode('_',$temp);
        $this->model = end($temp2);
        $this->table = strtolower($this->model) . 's';
    }
}