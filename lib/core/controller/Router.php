<?php

class Core_Controller_Router {

    /**
     * Maps the request url to the appropriate action controller and its method
     *
     * @param $url String to be used for mapping
     */
    public function route($url) {
        try {
            $urlArray = explode('/', $url);
            $helper = App::getHelper();
            $request = App::getModel('core/request');

            # The first part of the url is the module
            $module = !empty($urlArray[0]) ? ucfirst(strtolower($urlArray[0])) : 'Admin';
            array_shift($urlArray);

            # The second part of the url is the controller
            $controllerName = isset($urlArray[0]) ? ucfirst(strtolower($urlArray[0])) : 'Index';
            $request->setControllerName(strtolower($controllerName));
            array_shift($urlArray);

            # The third part of the url is the action
            $actionMethod = isset($urlArray[0]) ? $urlArray[0] . 'Action' : 'indexAction';
            $request->setActionName($actionMethod);
            array_shift($urlArray);

            # The final part of the url ar the parameters
            $queryString = count($urlArray) == 1 ? $urlArray[0] : $urlArray;

            $config = $helper->getConfig($module);

            if($config) {
                $actionController = $helper->getControllerClass($config,$controllerName);
                if(!class_exists($actionController)) {
                    header('Location: /' . strtolower($module));
                }
            }
            else {
                $actionController = $module . '_Controller_' . $controllerName;
                if(!class_exists($actionController)) {
                    # Don't know what the fuck to do here
                    header('Location: /admin');
                    die();
                }
            }

            $dispatch = new $actionController;

            if($dispatch && method_exists($dispatch,$actionMethod)) {
                if (empty($queryString)) {
                    $dispatch->$actionMethod();
                } else {
                    $dispatch->$actionMethod($queryString);
                }
            }
            else {
                # Error generation
                header('Location: /'  . strtolower($module));
            }

        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . '<br />';
        }
    }
}