<?php

class Core_Controller_Router {

    function __construct() {
      // Do nothing
    }

    function route($url) {

      $urlArray = explode('/',$url);

      # The first part of the url is the module
      $module = isset($urlArray[0]) ? ucfirst($urlArray[0]) : '';
      array_shift($urlArray);

      # The second part of the url is the controller
      $controller = isset($urlArray[0]) ? $urlArray[0] : '';
      array_shift($urlArray);

        $model = ucfirst($module) . '_Model_' .trim(ucfirst($controller),'s');

      # The third part of the url is the action
      $action = isset($urlArray[0]) ? $urlArray[0] : '';
      array_shift($urlArray);

      # The final part of the url ar the parameters
      $query = $urlArray;

      if(empty($controller)) {
        # Redirect to the default controller
        $controller = 'index';
      }

      if(empty($action)) {
        # Redirect to the index page
        $action = 'index';
      }

      $controllerName = $controller;
      $validController = (int) class_exists(ucfirst($module) . '_Controller_' . ucfirst($controllerName));
      $controller = $validController ? ucfirst($module) . '_Controller_' . ucfirst($controllerName) : Core_Model_Helper::map_route($url) . 'Index';
      $dispatch = new $controller($model,$controllerName, $action);

      if(method_exists($controller, $action)) {
        call_user_func_array(array($dispatch, $action), $query);
      }else {
        # Error generation code here
      }
  }
}