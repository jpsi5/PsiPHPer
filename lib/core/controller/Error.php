<?php
# Move to model directory
class Core_Controller_Error extends Core_Controller_Base{

    function __construct() {
        parent::__construct();
        echo 'This is an error <br />';

        //$this->view->render('error/index');
    }

    function view($arg = false) {
        echo 'This is a view from Error: ' . $arg . '<br />';
        var_dump($arg);
    }
}