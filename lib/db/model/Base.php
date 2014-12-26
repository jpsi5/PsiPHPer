<?php

class Db_Model_Base extends Db_Model_SQLQuery {

    private $model;
    protected $origData = array();
    protected $data = array();

    public static function getInstance(){
        static $_instance = null;
        if(is_null($_instance)) {
            $_instance = new static();
        }
        return $_instance;
    }

    public function __set($property,$value) {
        $this->data[$property] = $value;
        $this->origData[$property] = $value;
    }

    public function __get($property) {
        if(array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }
        App::getHelper('core/base')->triggerReferenceError($property);
        return null;
    }

    public function __call($name,$arguments) {
        $matches = preg_split('#([A-Z][^A-Z]*)#', $name , null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $method = array_shift($matches);
        $property = '';
        foreach($matches as $partialPropertyName) {
            
        }
        if(strstr($name,'get')) {
            $property = strtolower(str_replace('get','',$name));
            return $this->$property;
        }else if(strstr($name,'set')) {
            $property = strtolower(str_replace('set','',$name));
            $this->$property = !empty($arguments) ? $arguments[0] : null;
        }else if(strstr($name,'unset')) {
            $property = strtolower(str_replace('unset','',$name));
            $this->$property = null;
        }else if(strstr($name,'has')) {
            $property = strtolower(str_replace('has','',$name));
            return array_key_exists($property, $this->data) ? true : false;
        }

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

    public function load($id) {
        $result = $this->select('id', $id);
        foreach($result[0] as $key => $resultData) {
            $this->data[$key] = $resultData;
        }
    }

    public function save() {}

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