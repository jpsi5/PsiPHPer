<?php
# Move to controller directory
abstract Class Core_Controller_Base {

	private $controller;
	private $action;

	function __construct($controller,$action) {
		$this->controller = $controller;
		$this->action = $action;
	}

    function index() {
    	echo "hello world";
    }
}