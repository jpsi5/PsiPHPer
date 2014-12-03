<?php
# Move to controller directory
abstract Class Core_Controller_Base {

	protected $controller;
	protected $action;
    protected $model;
    protected $template;


	function __construct() {

	}

    function __call($name, $arguments) {
        #Default view to generate

        #Generate this view if bad_request_handle is set in config.xml
        echo 'Default action';
    }

    function set($name, $value) {
        $this->template->set($name,$value);
    }

//    function __destruct() {
//        $this->template->render();
//    }
}