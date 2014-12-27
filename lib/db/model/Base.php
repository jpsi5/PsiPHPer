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
    }

    public function __get($property) {
        if(array_key_exists($property, $this->data)) {
            return $this->data[$property];
        }
        App::getHelper('core/base')->triggerReferenceError($property);
        return null;
    }

    public function __call($name,$arguments) {
        # Get the method (i.e. get,set,unset...) and the property name
        $matches = preg_split('#([A-Z][^A-Z]*)#', $name , null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $method = array_shift($matches);
        $property = '';

        # Formatting the property name of the object to match the column field
        # name format in a database
        if(count($matches) == 1) {
            $property = strtolower($matches[0]);
        } else {
            $property = implode('_',$matches);
            //$property = strtolower($property);
            # Validate that property is the name of a column in the table
            $property = $this->validColumnName(strtolower($property)) ? strtolower($property) : lcfirst(implode('',$matches));
        }



        switch($method) {
            case 'get':
                return $this->$property;
                break;
            case 'set':
                $this->$property = !empty($arguments) ? $arguments[0] : null;
                break;
            case 'unset':
                $this->$property = null;
                break;
            case 'has':
                return array_key_exists($property, $this->data) ? true : false;
                break;
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
        $result = $this->select($this->primaryKey, $id);
        foreach($result[0] as $key => $resultData) {
            $this->data[$key] = $resultData;
            $this->origData[$key] = $resultData;
        }
    }

    public function save() {
        $fields = $this->getColumnNames();

        # Format column order
        $args = array();
        foreach($fields as $field) {
            $args[$field] = $this->data[$field];
        }

        # Remove the primary key
        unset($args[$this->primaryKey]);

        if(empty($this->origData))
        {
            # Creating a new entry
            $args = implode(',',$args);
            $this->insert($args);
        } else {


            $args[$this->primaryKey] = $this->data[$this->primaryKey];

            # Updating and existing entry
            $args = implode(',',$args);
            $this->update($args);
        }

    }

    public function delete() {

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

        # Set the primary key property. This will prevent running more code
        # during other method calls
        $this->primaryKey = $this->getPrimaryKeyName();
    }
}