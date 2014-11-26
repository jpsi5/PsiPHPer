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
    $url = 'admin/error/view';
}

# Define and set the the include paths
$allPaths = array(
    ROOT . DS,
    ROOT . 'lib/',
    ROOT . 'app/',
    get_include_path()
);

set_include_path(implode(PATH_SEPARATOR,$allPaths));

# Include autoloader and register example Core_Model_Autoloader::register()
require('core/model/Autoloader.php');
$autoloader = new Autoloader();
$autoloader->register();

# Create the router that will load the appropriate controller
$router = new Core_Controller_Router($url);
$router->dispatch();




