<?php

class Core_Controller_Router {

    function route($url) {

        $urlArray = explode('/',$url);

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
        $query = $urlArray;

        if(empty($controllerName)) {
            # Redirect to the default controller
            $controllerName = 'Error';
        }

        if(empty($action)) {
            # Redirect to the index page
            $action = 'index';
        }

        # Validate the controller
        $validController = (int) class_exists(ucfirst($module) . '_Controller_' . ucfirst($controllerName));
        $controller = $validController ? ucfirst($module) . '_Controller_' . ucfirst($controllerName) : Core_Model_Helper::map_route($url);
        $dispatch = new $controller;

        if(method_exists($controller, $action)) {
            # TODO: Change this line to $dispatch->$action. What if the action required arguments?
            call_user_func_array(array($dispatch, $action), $query);
        }else {
            # TODO: Error generation code here
        }
  }
}