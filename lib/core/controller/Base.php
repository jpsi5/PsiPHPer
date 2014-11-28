<?php
# Move to controller directory
abstract Class Core_Controller_Base {

	protected $controller;
	protected $action;
    protected $model;
    protected $template;


	function __construct($model,$controller,$action) {
		$this->controller = $controller;
		$this->action = $action;
        $this->model = $model;
        $this->$model = new $model;
        $this->template = new Core_Model_Template($controller,$action);
	}

    function set($name, $value) {
        $this->template->set($name,$value);
    }

    function __destruct() {
        $this->template->render();
    }

    function index() {
    	echo "hello world";
    }
}