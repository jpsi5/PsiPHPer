<?php

# Set error reporting if the environment is development
error_reporting(E_ALL);
if (DEVELOPMENT_ENVIRONMENT) {
    ini_set('display_errors','On');
} else {
    ini_set('display_errors','Off');
    ini_set('log_errors', 'On');
    ini_set('error_log',ROOT . 'lib/error.log');
}

# Get the url if it is set, otherwise work your magic
if(isset($_GET['url'])) {
    $url = rtrim($_GET['url'],'/');
}
else {
    # TODO: Change this sooner or later to avoid instantiating an abstract class.
    $url = '';
}

# Include autoloader
require('core/model/Autoloader.php');
$autoloader = new Autoloader();

# Load all the configuration files and set the module for the current application
//Core_Model_Helper::load_configs();
//Core_Model_Helper::set_module($url);
$helper = getHelper('core/base');
$helper->load_configs();
$helper->set_module($url);

#############################################################
# User-defined Functions                                    #
#############################################################
function getModel($path) {
    $str = strtolower($path);
    $pathArray = explode(DS,$str);
    try {
        if(count($pathArray) < 2) {
            throw new Exception('Invalid argument. Usage: object getModel ( string $string )');
        }

        # Build the name of the model
        $model = ucfirst($pathArray[0]) . '_Model_' . ucfirst($pathArray[1]);

        # Verify that $model is a valid class name.
        if(class_exists($model)) {
            return $model::getInstance();
        }
        else {
            throw new Exception('Class [' . $model . '] not found.');
        }
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . '<br />';
    }
}

function getHelper($path){
    try {
        $str = strtolower($path);
        $pathArray = explode(DS,$str);
        if(count($pathArray) < 2) {
            throw new Exception('Invalid argument. Usage: object getHelper ( string $string )');
        }

        # Build the name of the helper
        $helper = ucfirst($pathArray[0]) . '_Helper_' . ucfirst($pathArray[1]);

        # Verify that $model is a valid class name.
        if(class_exists($helper)) {
            # TODO: May need to change depending on how _Helpers_ classes are accessed
            return $helper::getInstance();
        }
        else {
            throw new Exception('Class [' . $helper . '] not found.');
        }
    } catch(Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . '<br />';
    }
}