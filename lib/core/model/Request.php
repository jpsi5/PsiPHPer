<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 12/29/14
 * Time: 5:34 PM
 */

class Core_Model_Request extends Core_Model_Singleton{

    private $controllerName;
    private $actionName;

    protected function _init() {
        $this->registerGlobals();
    }

    protected function unregisterGlobals() {
        if (ini_get('register_globals')) {
            $enVars = array('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($enVars as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    protected function registerGlobals() {
        $enVars = array('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($enVars as $value) {
            $this->data[$value] = array();
            foreach ($GLOBALS[$value] as $key => $var) {
                $this->data[$value][$key] = $var;
            }
        }
    }

    public function getMethod() {
        return $this->data['_SERVER']['REQUEST_METHOD'];
    }

    public function isPost() {
        return $this->getMethod() == 'POST' ? true : false;
    }

    public function hasPost() {
        return empty($this->data['_POST']) ? false : true;
    }

    public function isGet() {
        return $this->getMethod() == 'GET' ? true : false;
    }

    public function hasGet() {
        return empty($this->data['_GET']) ? false : true;
    }

    public function getControllerName() {
        return $this->controllerName;
    }

    public function setControllerName($name) {
        $this->controllerName = $name;
    }

    public function getActionName() {
        return $this->actionName;
    }

    public function setActionName($name) {
        $this->actionName = $name;
    }

    public function getParam($key) {
        foreach($this->data as $enVar => $array)
        {
            if(array_key_exists($key,$array)) {
                return $array[$key];
            }
        }
        return false;
    }

    public function setParam($key, $value) {}

    public function getParams() {

        # Return POST request variables
        if($this->data['_POST']) {
            return $this->data['_POST'];
        }

        # Return GET request variables
        if($this->data['_GET']) {
            return $this->data['_GET'];
        }

    }

    public function setParams(array $array) {}


}