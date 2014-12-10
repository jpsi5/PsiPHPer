<?php
class Core_View_Layout_Base {
    private $_output = array();
    private $_blocks = array();
    private static $_instance;

    private function __construct(){}

    public static function getInstance(){
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}