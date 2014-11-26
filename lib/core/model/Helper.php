<?php
# Move to model or helper directory. Your choice for processing helpers.
class Core_Model_Helper {

    static public $all_configs;
	
	public function __construct() {
		//echo 'We are inside helper' . '<br />';
	}

	public function other($arg = false) {
		echo 'We are in other' . '<br />';
		echo 'Optional: ' . $arg . '<br />'; 
	}

    public static function map_route($url) {

        $module = explode(DS, $url);

        # Get all the config.xml files from each module
        $configFileList = glob('[app|lib]*/*/config.xml');

        # Search each config file for the requested uri
        foreach($configFileList as $file) {
            $xmlFile = simplexml_load_file($file);
            if($module[0] == $xmlFile->route->uri) {

                # Retrieve the directory for the controllers in config.xml
                $dir = $xmlFile->route->dir;

                # Ignoring any appending or prepending directory separators (pre-caution)
                rtrim($dir,'/');
                ltrim($dir,'/');

                # Building the class name of the controller
                $partialClassNameArray = explode(DS, $dir);
                $partialClassName = '';

                foreach($partialClassNameArray as $part) {
                    $partialClassName = $partialClassName . ucfirst($part) . '_';
                }
                return $partialClassName;
            }
            else {
                $partialClassName = "Core_Controller";
            }
        }
        return $partialClassName;
        //$className = $className . $controllerName;


    }
}