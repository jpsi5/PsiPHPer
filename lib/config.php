<?php

# Configuration variables

define ('DEVELOPMENT_ENVIRONMENT',true);
define ('INVALID_FORM_DATA', 800);

define('DB_NAME', 'todo');
define('DB_USER', 'root');
define('DB_PASSWORD', 'M@ster87!');
define('DB_HOST', 'localhost');

define('FACEBOOK_SDK_V4_SRC_DIR', ROOT . '/api/facebook-php-sdk-v4-4.0-dev/src/Facebook/');
require ROOT . 'api/facebook-php-sdk-v4-4.0-dev/autoload.php';