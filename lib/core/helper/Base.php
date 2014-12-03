<?php
# Move to model or helper directory. Your choice for processing helpers.
class Core_Helper_Base {

    private $all_configs = array();
    private $working_module;
    private static $instance;

    private function __construct() {}

    # Returns the singleton instance of this class
    public static function getInstance(){
        if(is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function map_route($url) {

        $urlArray = explode(DS, $url);
        $module = $urlArray[0];

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
            }
            else {
                $partialClassName = "Core_Controller_Error";
            }
        }
        return $partialClassName;
    }

    public function load_configs() {

        # Get all the config files
        $configFileList = glob('[app|lib]*/*/config.xml');

        foreach($configFileList as $file) {
            $this->all_configs[] = simplexml_load_file($file);
        }
    }

    public function set_module($url) {
        $urlArray = explode(DS, $url);
        $this->working_module = $urlArray[0];
    }
}