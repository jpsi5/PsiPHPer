<?php

# Set error reporting if the environment is development
error_reporting(E_ALL);
if (DEVELOPMENT_ENVIRONMENT) {
    ini_set('display_errors','On');
} else {
    ini_set('display_errors','Off');
    ini_set('log_errors', 'On');
    # TODO: create or set the log file here
}

# Get the url if it is set, otherwise work your magic
if(isset($_GET['url'])) {
    $url = $_GET['url'];
}
else {
    # TODO: Change this sooner or later to avoid instantiating
    # an abstract class.
    $url = '';
}

# Include autoloader and register example Core_Model_Autoloader::register()
require('core/model/Autoloader.php');
$autoloader = new Autoloader();

# Include helper functions that will be used throughout the application
require('helpers.php');

# Load all the configuration files into a single variable
Core_Model_Helper::load_configs();

# Set the module for the current application;
Core_Model_Helper::set_module($url);

# Create the router that will load the appropriate controller
$router = new Core_Controller_Router();
$router->route($url);