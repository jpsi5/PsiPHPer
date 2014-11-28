<?php
# Move to model directory
class Core_Controller_Index extends Core_Controller_Base{

    function __construct($controller,$action) {
        parent::__construct($controller,$action);
        echo 'From ['. $this->controller .']: Controller Created! <br />';
    }

    function view($arg = false) {
        echo 'This is a view from [' . $this->controller . ']: ' . $arg . '<br />';
    }
}