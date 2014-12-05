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
$helper = App::getHelper('core/base');
$helper->loadConfigs();
$helper->setModule($url);

