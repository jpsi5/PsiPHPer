<?php

# Set error reporting if the environment is development
if (DEVELOPMENT_ENVIRONMENT == true) {
    error_reporting(E_ALL);
    ini_set('display_errors','On');

} else {
    error_reporting(E_ALL);
    ini_set('display_errors','Off');
    ini_set('log_errors', 'On');
    # *!* REMEMBER *!* to create or set the log file here
}

# Get the url if it is set, otherwise work your magic
if(isset($_GET['url'])) {
    $url = $_GET['url'];
}
else {
    # *!* REMEMBER *!* Change this sooner or later to avoid instantiating
    # an abstract class.
    $url = '';
}

# Include autoloader and register example Core_Model_Autoloader::register()
require('core/model/Autoloader.php');
$autoloader = new Autoloader();

# Load all the configuration files into a single variable
Core_Model_Helper::load_configs();

# Create the router that will load the appropriate controller
$router = new Core_Controller_Router();
$router->route($url);