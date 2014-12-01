<?php
# Move to controller directory
abstract Class Core_Controller_Base {

	protected $controller;
	protected $action;
    protected $model;
    protected $template;


	function __construct() {

	}

    function set($name, $value) {
        $this->template->set($name,$value);
    }

    function __destruct() {
        $this->template->render();
    }
}