<?php

class Core_Model_Base extends Core_Model_SQLQuery {

    protected $model;

    public function __construct() {
        $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $this->model = get_class($this);
        $this->table = strtolower($this->model) . 's';
    }
}