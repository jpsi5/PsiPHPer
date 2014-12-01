<?php
class Core_Model_Template {

    protected $variables = array();
    protected $controller;
    protected $action;

    function __construct($controller,$action) {
        $this->controller = $controller;
        $this->action = $action;
    }

    # Set variables
    function set($name,$value) {
        $this->variables[$name] = $value;

    }

    # Display template
    function render() {

    }

}