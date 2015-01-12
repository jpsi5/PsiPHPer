<?php

require (ROOT. 'lib/shared.php');

# Create the router that will load the appropriate controller
$app = new App();
$app->map($url);


#############################################################
# Utility Class                                             #
#############################################################
class App {
    private $router;
    private $dbConnection;

    public function map($url) {

        # Create the request object
        $request = $this->getModel('core/request');

        # Connect with the database
        $this->dbConnection = App::getModel('db/SQLConn');
        $this->dbConnection->connect();

        # Routing the url
        $this->router = new Core_Controller_Router();
        $this->router->route($url);

        # Close the database connection
        $this->dbConnection->disconnect();
    }

    public static function getModel($path) {
        return self::_getClass($path,'Model');
    }

    public static function getRequest() {
        return self::_getClass('core/request','Model');
    }

    public static function getHelper($path = false) {
        return $path ? self::_getClass($path,'Helper') : self::_getClass('core/base','Helper');
    }

    public static function getLayout($path) {
        return self::_getClass($path,'View_Layout');
    }

    public static function getBlock($path) {
        return self::_getClass($path,'View_Block');
    }

    /**
     * Gets a singleton instance of the specified class
     *
     * @param string $path The uri used to instantiate the class
     * @param string $type The class to return
     * @return object Returns an instance of the requested class
     */
    protected static function _getClass($path = null, $type = null) {
        if($type && $path) {
            try {
                $str = $path;
                $pathArray = explode(DS, $str);
                if (count($pathArray) < 2) {
                    throw new Exception('Invalid argument. Usage: module/file[_dir_]');
                }

                # Build the name of the model
                $className = ucfirst(strtolower($pathArray[0])) . '_' . ucfirst($type) . '_' . ucfirst($pathArray[1]);

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
        return null;
    }

    /**
     * Returns the directory of the module
     *
     * @param $moduleName The name of the module
     * @return string The directory of the module
     */
    public static function getModuleDirectory($moduleName) {

        $modulePath = glob('[app|lib]*' . DS . $moduleName . DS);
        $trueModulePath = glob('[app|lib]*' . DS . self::getHelper()->getTrueModule() . DS);
        if(count($modulePath) == 1) {
            return ROOT . $modulePath[0];
        } else if(count($trueModulePath) == 1) {
            return ROOT . $trueModulePath[0];
        }
        return null;
    }

    /**
     * Triggers an event based on the $eventName
     *
     * @param string $eventName
     */
    public static function fireEvent($eventName = null) {
        # Retrieve the configuration files for the current module
        # and the core module. Events should only be concerned with
        # configurations in the core and current module
        try {
            if(is_null($eventName)) {
                throw new Exception('App::fireEvent(string $eventName): Must specify an event name parameter.');
            }
            $modConfig = self::getHelper()->getConfig();
            $coreConfig = self::getHelper()->getConfig('core');

            # Get the event class and method
            $eventClass = ($modConfig->events->$eventName) ? $modConfig->events->$eventName->class->__toString() : $coreConfig->events->$eventName->class->__toString();
            $eventMethod = ($modConfig->events->$eventName) ? $modConfig->events->$eventName->method->__toString() : $coreConfig->events->$eventName->method->__toString();

            # Instantiate the class and call its method
            $eventInstance = new $eventClass;
            $eventInstance->$eventMethod();
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . '<br/>';
        }

    }
}

