<?php

class Core_Controller_Router {

    private $url;
    private $urlArray;

    function __construct($url) {

        $this->url = $url . '/';

        # Remove any extra directory separators
        rtrim($this->url, '/');

        # Expand the url [module, controller, action, params,...]
        $this->urlArray = explode('/', $url);
    }

    function dispatch(){

        # Set the module, controller, action, and query string
        if(isset($this->urlArray[0])) {
            $module = $this->urlArray[0];
            array_shift($this->urlArray);
            if(isset($this->urlArray[0])) {
                $controllerName = ucfirst($this->urlArray[0]);
                array_shift($this->urlArray);
                if(isset($this->urlArray[0])) {
                    $action = $this->urlArray[0];
                    array_shift($this->urlArray);
                    if(isset($this->urlArray[0])) {
                        $queryString = $this->urlArray;
                    }
                }
            }
        }

        $ctrlClassName = Core_Model_Helper::map_route($this->url) . $controllerName;

        # Create the controller
        if(class_exists($ctrlClassName)) {
           if(isset($queryString) && isset($action) && isset($controllerName) && isset($module)) {
               $controller = new $ctrlClassName;
               if(method_exists($controller, $action)) {
                   call_user_func_array(array($controller, $action),$queryString);
               }
           } else if (isset($action) && isset($controllerName) && isset($module)) {
               $controller = new $ctrlClassName;
               if(method_exists($controller, $action)) {
                   $controller->{$action}();
               }
           } else if (isset($controllerName) && isset($module)) {
               $controller = new $ctrlClassName;
               $controller->view();
           }
        }


        else {
            # May need to create a error page view here
            echo 'The requested url cannot be found';
        }
    }
}
