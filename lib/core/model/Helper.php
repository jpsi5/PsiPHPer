<?php
# Move to model or helper directory. Your choice for processing helpers.
class Core_Model_Helper {

    static public $all_configs = array();
    static public $working_module;

    public static function map_route($url) {

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


    public static function load_configs() {

        # Get all the config files
        $configFileList = glob('[app|lib]*/*/config.xml');

        foreach($configFileList as $file) {
            self::$all_configs[] = simplexml_load_file($file);
        }
    }

    public static function set_module($url) {
        $urlArray = explode(DS, $url);
        self::$working_module = $urlArray[0];
    }
}