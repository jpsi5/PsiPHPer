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
        $this->unregisterGlobals();
    }

    protected function unregisterGlobals() {
        if (ini_get('register_globals')) {
            $enVars = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($enVars as $value) {
                if(array_key_exists($value,$GLOBALS)) {
                    foreach ($GLOBALS[$value] as $key => $var) {
                        if ($var === $GLOBALS[$key]) {
                            unset($GLOBALS[$key]);
                        }
                    }
                }
            }
        }
    }

    protected function registerGlobals() {
        $enVars = array('_SESSION','_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($enVars as $value) {
            $this->data[$value] = array();
            if(array_key_exists($value,$GLOBALS)){
                foreach ($GLOBALS[$value] as $key => $var) {
                    $this->data[$value][$key] = $var;
                }
            }
        }
        $this->data['_STATUS'] = array();
        $this->data['_STATUS']['FORM_STATUS'] = '';
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

    public function getSession($name) {
        return array_key_exists($name,$this->data['_SESSION']) ? $this->data['_SESSION'][$name] : false;
    }

    public function setSession($name,$value) {
        if(isset($name) && isset($value)) {
            $_SESSION[$name] = $value;
            $this->data['_SESSION'][$name] = $value;
        }
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

    public function setParam($key, $value) {
        foreach($this->data as $enVar => $array)
        {
            if(array_key_exists($key,$array)) {
                $array[$key] = $value;
            }
        }
    }

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

    public function getData($key = false) {
        return $this->data;
    }

    public function setParams(array $array) {}

    /**
     * Redirects the current controller
     *
     * @param string $url The url used to redirect the browser
     * @throws Exception when the url is invalid
     */
    public function redirect($url) {

        if($this->isPost() && $this->getParam('FORM_STATUS') == INVALID_FORM_DATA) {
            return;
        }

        # Clean up the url
        $cleanedUrl = ltrim($url, '/');
        $cleanedUrl = rtrim($cleanedUrl, '/');

        # Rules each route must follow
        $validRoutes = array(
            'mcai'  => '/^([\w]+|\*?)\/([\w]+|\*?)\/([\w]+|\*?)(\/([\d]+))+$/',
            'mca'   => '/^([\w]+|\*?)\/([\w]+|\*?)\/([\w]+|\*?)$/',
            'mc'    => '/^([\w]+|\*?)\/([\w]+|\*?)$/',
            'm'     => '/^([\w]+|\*)$/'
        );

        # Verify the format of the format of the url
        $validUrl = false;
        foreach($validRoutes as $validRoute) {
            if(preg_match($validRoute,$cleanedUrl)) {
                $validUrl = true;
                break;
            }
        }

        if($validUrl){
            # Split the url to evaluate it by parts
            $redirectUrl = array();
            $urlArray = explode('/',$cleanedUrl);
            foreach ($urlArray as $key => $urlPart) {
                if($urlPart == '*') {
                    switch($key) {
                        case 0:
                            $redirectUrl[] = App::getHelper()->getModule();
                            break;
                        case 1:
                            $redirectUrl[] = $this->getControllerName();
                            break;
                        case 2:
                            $redirectUrl[] = $this->getActionName();
                            break;
                        #TODO: Use a default case to get query string values from the requested url
                    }
                }
                else {
                    $redirectUrl[] = strtolower($urlPart);
                }
            }

            # Reconstruct the url
            $redirectUrl = implode('/',$redirectUrl);

            # Redirect to the requested url
            header('Location: /' . $redirectUrl);
        }
        else {
            throw new Exception("In method " . get_class($this) . "::redirect(): '" . $url . "' is not a valid url.");
        }
    }


}