<?php

class Core_Helper_Base extends Core_Model_Singleton{

    private $_allConfigs = array();
    private $_workingModule;

    /**
     * Loads all the the configuration files into an array
     *
     * @param void
     * @return void
     */
    public function loadConfigs() {

        # Get all the config files
        $configFileList = glob('[app|lib]*/*/config.xml');

        foreach($configFileList as $file) {
            $this->_allConfigs[] = simplexml_load_file($file);
        }
    }

    /**
     * Returns the configuration file of the $module
     *
     * @param string $module String used to find appropriate config file
     * @return SimpleXMLElement $config, NULL if no module is found
     */
    public function getConfig($module) {

        foreach($this->_allConfigs as $config) {
            if(strtolower($module) == strtolower($config->route->uri)) {
                return $config;
            }
        }
        return null;
    }

    /**
     * Builds the class name of the controller
     *
     * @param SimpleXMLElement $config
     * @param string $actionControllerName
     * @return string Returns a class name of the controller
     */
    public function getControllerClass($config,$actionControllerName) {
        $pathArray = explode(DS,$config->route->dir);
        $actionController = '';
        foreach($pathArray as $path) {
            $actionController = $actionController . ucfirst($path) . '_';
        }
        $actionController .= ucfirst($actionControllerName);

        return $actionController;
    }

    /**
     * Gets the database credentials from the configuration file
     *
     * @param string $module
     * @return array|null Returns null if the module does not exist or database configuration not present
     */
    public function getDbCredentials($module) {
        $db = array();
        $configPath = 'app/config.xml';
        $config = simplexml_load_file($configPath);
        //$config = $this->getConfig($module);
        try {
            if ($config && $config->database) {
                $db["host"] = $config->database->host->__toString();
                $db["user"] = $config->database->user->__toString();
                $db["password"] = $config->database->key->__toString();
                $db["name"] = $config->database->name->__toString();
            } else {
                $db = null;
                Throw new Exception('Cannot find database for the module: ' . $module);
            }
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . '<br />';
        }

        return $db;
    }

    /**
     * Returns the name of the current module
     *
     * @param void
     * @return string Return the name of the current module specified by the url
     */
    public function getModule() {
        return $this->_workingModule;
    }

    /**
     * Sets the _workingModule to the current module specified by the url
     *
     * @param string $url
     * @return void
     */
    public function setModule($url) {
        if($url){
            $urlArray = explode(DS,$url);
            $module = $urlArray[0];
            $this->_workingModule = $module;
        }
        else {
           $this->_workingModule = 'admin';
        }
    }

    public function triggerReferenceError($name) {
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via ' . $trace[1]['function'] . ': ' . $name .
            ' in ' . $trace[1]['file'] .
            ' on line ' . $trace[1]['line'],
            E_USER_NOTICE);
    }

    /**
     * Gets the name of the class the method is called in
     *
     * @param void
     * @return string Returns the class name
     */
    public function getCallingClass() {
        return $this->_getCalling('class');
    }

    /**
     * Gets the name of the action method that the current method
     * is called in.
     *
     * @param void
     * @return string Returns the action method name or other method name
     */
    public function getCallingMethodName() {
        $callingFunc = $this->_getCalling('function');
        if(strstr($callingFunc, 'Action')){
            $actionName = strtolower(str_replace('Action','',$callingFunc));
            return $actionName;
        }
        return $callingFunc;

    }

    /**
     * Returns the name of the calling class or method
     *
     * @param bool|string $type
     * @return string Returns the value at the specified key [$type]
     */
    protected function _getCalling($type = false) {
        $e = new Exception();
        $trace = $e->getTrace();
        return $trace[3][$type];
    }


}