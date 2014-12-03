<?php

class Core_Controller_Router {

    function route($url) {

        try {

            $urlArray = explode('/', $url);
            $helper = getHelper('core/base');


            # The first part of the url is the module
            $module = isset($urlArray[0]) ? ucfirst(strtolower($urlArray[0])) : '';
            array_shift($urlArray);

            # The second part of the url is the controller
            $controllerName = isset($urlArray[0]) ? ucfirst(strtolower($urlArray[0])) : '';
            array_shift($urlArray);

            # The third part of the url is the action
            $action = isset($urlArray[0]) ? $urlArray[0] : '';
            array_shift($urlArray);

            # The final part of the url ar the parameters
            $queryString = count($urlArray) == 1 ? $urlArray[0] : $urlArray;

            if (empty($controllerName)) {
                # Redirect to the default controller
                $controllerName = 'Error';
            }

            if (empty($action)) {
                # Redirect to the index page
                $action = 'index';
            }

            # Validate the controller and use mapping in case of differing module names between the url and directory
            $validController = (int)class_exists(ucfirst($module) . '_Controller_' . ucfirst($controllerName));
            $controller = $validController ? ucfirst($module) . '_Controller_' . ucfirst($controllerName) : $helper->map_route($url);

            # Dispatch the valid controller
            $dispatch = class_exists($controller) ? new $controller : null;

            # TODO change this implementation in order to remove concern of valid actions
            if(method_exists($controller, $action) && $dispatch != null) {
                if(empty($queryString))
                {
                    $dispatch->$action();
                } else {
                    $dispatch->$action($queryString);
                }
            }else {
                # TODO: Error generation code here
            }

        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . '<br />';
        }
  }
}