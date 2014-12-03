<?php

include (ROOT. 'lib/shared.php');

# Create the router that will load the appropriate controller
$router = new Core_Controller_Router();
$router->route($url);