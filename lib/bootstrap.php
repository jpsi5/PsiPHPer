<?php

include (ROOT. 'lib/shared.php');

# Create the router that will load the appropriate controller
$app = new App();
$app->map($url);

#############################################################
# Utility Class                                             #
#############################################################
class App {
    private $router;

    public function map($url) {
        $this->router = new Core_Controller_Router();
        $this->router->route($url);
    }

    public static function getModel($path) {
        return self::_getClass($path,'Model');
    }

    public static function getHelper($path) {
        return self::_getClass($path,'Helper');
    }

    public static function getLayout($path) {
        return self::_getClass($path,'View_Layout');
    }

    public static function getBlock($path) {
        return self::_getClass($path, 'View_Block');
    }

    /**
     * Gets a singleton instance of the specified class
     *
     * @param string $path
     * @param string $type
     * @return object Returns an instance of the requested class
     */
    protected static function _getClass($path = null, $type = null) {
        if($type && $path) {
            try {
                $str = strtolower($path);
                $pathArray = explode(DS, $str);
                if (count($pathArray) < 2) {
                    throw new Exception('Invalid argument. Usage: module/file[_dir_]');
                }

                # Build the name of the model
                $className = ucfirst($pathArray[0]) . '_' . ucfirst($type) . '_' . ucfirst($pathArray[1]);

                # Verify that $model is a valid class name.
                if (class_exists($className)) {
                    if(method_exists($className,'getInstance')) {
                        return $className::getInstance();
                    }
                    else {
                        return new $className;
                    }
                } else {
                    throw new Exception('Class [' . $className . '] not found.');
                }
            } catch (Exception $e) {
                echo 'Caught exception: ' . $e->getMessage() . '<br />';
            }
        }
    }
}