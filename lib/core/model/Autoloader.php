<?php

class Autoloader {

    public function __construct() {

        # Define and set the the include paths
        $allPaths = array(
            ROOT . DS,
            ROOT . 'lib/',
            ROOT . 'app/',
            get_include_path()
        );

        set_include_path(implode(PATH_SEPARATOR,$allPaths));

        $this->register();
    }

    public function register() {
        spl_autoload_register(function ($className) {

            # Expand the class name
            $pathArray = explode('_', $className);

            $pathLength = count($pathArray);
            foreach($pathArray as $key => $p) {

                # Get the path and file name
                $path = $pathLength != 1 ? array_slice($pathArray,0,$key + 1) : array($p);
                $file = $pathLength != count($path) ? array_slice($pathArray,$key + 1) : array();

                # Format the path and file name string
                $path = strtolower(implode('/',$path)) . DS;
                $file = implode('_',$file);

                $finalPath = $path . $file . '.php';

                if(stream_resolve_include_path($finalPath) != false) {
                    require($finalPath);
                    break;
                }
            }
        });
    }
}