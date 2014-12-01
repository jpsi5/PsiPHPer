<?php

class Core_Controller_Router {

    function route($url) {

        $urlArray = explode('/',$url);

        # The first part of the url is the module
        $module = isset($urlArray[0]) ? ucfirst($urlArray[0]) : '';
        array_shift($urlArray);

        # The second part of the url is the controller
        $controller = isset($urlArray[0]) ? $urlArray[0] : '';
        array_shift($urlArray);

        # The third part of the url is the action
        $action = isset($urlArray[0]) ? $urlArray[0] : '';
        array_shift($urlArray);

        # The final part of the url ar the parameters
        $query = $urlArray;

        # Build the model name
        $model = ucfirst($module) . '_Model_' .trim(ucfirst($controller),'s');

        if(empty($controller)) {
            # Redirect to the default controller
            $controller = 'error';
        }

        if(empty($action)) {
            # Redirect to the index page
            $action = 'index';
        }

        $controllerName = $controller;

        # Validate the controller
        $validController = (int) class_exists(ucfirst($module) . '_Controller_' . ucfirst($controller));
        $validMapping = (int) class_exists(Core_Model_Helper::map_route($url) . ucfirst($controller));

        if(!$validController) {

        }
        $controller = $validController ? ucfirst($module) . '_Controller_' . ucfirst($controllerName) : Core_Model_Helper::map_route($url) . 'Error';
        $dispatch = new $controller;

        if(method_exists($controller, $action)) {
            #TODO: Change this line to $dispatch->$action. What if the action required arguments
            call_user_func_array(array($dispatch, $action), $query);
        }else {
            # Error generation code here
        }
  }
}