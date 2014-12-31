<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 12/29/14
 * Time: 5:34 PM
 */

class Core_Model_Request extends Core_Model_Singleton{

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