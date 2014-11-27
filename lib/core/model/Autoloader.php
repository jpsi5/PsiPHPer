<?php

class Autoloader {

    public function __constructor() {

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
            $paths = explode('_', $className);

            # Get the file name of the class and remove
            # from the path array
            $classFileName = end($paths);
            array_pop($paths);


            $classPath = '';
            foreach($paths as $path) {
                $classPath .= lcfirst($path) . DS;
            }

            # Combine the class file name with its path
            $finalPath = $classPath . $classFileName . '.php';

            #
            if(stream_resolve_include_path($finalPath) != false) {
                require($finalPath);
            }
        });
    }
}