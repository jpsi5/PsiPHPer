<?php
# Move to model or helper directory. Your choice for processing helpers.
class Core_Helper_Base {

    private $allConfigs = array();
    private $workingModule;
    private static $instance;

    private function __construct() {}

    # Returns the singleton instance of this class
    public static function getInstance(){
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function mapRoute($url) {

        $urlArray = explode(DS, $url);
        $module = strtolower($urlArray[0]);

        # Get all the config.xml files from each module
        $configFileList = glob('[app|lib]*/*/config.xml');

        # Search each config file for the requested uri
        foreach($configFileList as $file) {
            $xmlFile = simplexml_load_file($file);
            if($module == $xmlFile->route->uri) {

                # Retrieve the directory for the controllers in config.xml
                $dir = $xmlFile->route->dir;

                # Ignoring any appending or prepending directory separators (pre-caution)
                rtrim($dir,'/');
                ltrim($dir,'/');

                # Building the class name of the controller
                $partialClassNameArray = explode(DS, $dir);
                $partialClassName = '';

                foreach($partialClassNameArray as $part) {
                    $part = strtolower($part);
                    $partialClassName = $partialClassName . ucfirst($part) . '_';
                }

                # Return the complete name if the controller name (urlArray[1]) is set
                if(isset($urlArray[1])) {
                    return $partialClassName . ucfirst($urlArray[1]);
                }
                else {
                    return $partialClassName . 'Index';
                }
            }
            else {
                $partialClassName = "Core_Controller_Error";
            }
        }
        return $partialClassName;
    }

    public function loadConfigs() {

        # Get all the config files
        $configFileList = glob('[app|lib]*/*/config.xml');

        foreach($configFileList as $file) {
            $this->allConfigs[] = simplexml_load_file($file);
        }
    }

    public function getConfig($module) {
        # Get all config file paths
        $configFileList = glob('[app|lib]*/*/config.xml');

        foreach($configFileList as $path) {
            $pathArray = explode(DS,$path);
            if(strtolower($module) == strtolower($pathArray[1])) {
                return simplexml_load_file($path);
            }
        }
        return null;
    }

    public function badRequestController() {

    }

    public function setModule($url) {
        $urlArray = explode(DS, $url);
        $this->workingModule = $urlArray[0];
    }
}