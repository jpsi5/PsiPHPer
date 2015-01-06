<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 12/29/14
 * Time: 10:36 AM
 */

abstract class Core_Model_Singleton {
    protected $data = array();
    protected $origData = array();

    final private function __construct() {}

    protected function _init() {}

    public static function getInstance(){
        static $_instance = null;
        if(is_null($_instance)) {
            $_instance = new static();
            $_instance->_init();
        }
        return $_instance;
    }

    public function __set($property,$value) {
        $this->data[$property] = $value;
    }

    public function __get($property) {
        if(array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }
        App::getHelper('core/base')->triggerReferenceError($property);
        return null;
    }

    public function getData($key = false) {
        return $this->_getData('data',$key);
    }

    public function getOrigData($key = false) {
        return $this->_getData('origData',$key);
    }

    protected function _getData($data,$key = false) {
        if($key) {
            return $this->$data[$key];
        }
        return $this->$data;
    }

    public function load($id) {}

    public function save() {}

}