<?php
# Move to model or helper directory. Your choice for processing helpers.
class Core_Helper_Base {

    private $allConfigs = array();
    private $workingModule;
    private static $instance;

    private function __construct() {}

    /**
     * Returns a singleton instance of this class
     *
     * @return Core_Helper_Base
     */
    public static function getInstance(){
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

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
            $this->allConfigs[] = simplexml_load_file($file);
        }
    }

    /**
     * Returns the configuration file of the $module
     *
     * @param string $module String used to find appropriate config file
     * @return SimpleXMLElement $config, NULL if no module is found
     */
    public function getConfig($module) {

        foreach($this->allConfigs as $config) {
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
        $dir = $config->route->dir;
        $pathArray = explode(DS,$dir);
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
        $config = $this->getConfig($module);
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
}