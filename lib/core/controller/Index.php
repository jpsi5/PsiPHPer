<?php
# Move to model directory
class Core_Controller_Index extends Core_Controller_Core{

    function __construct() {
        parent::__construct();
        echo 'We are in index <br />';

        //$this->view->render('error/index');
    }

    function view($arg = false) {
        echo 'This is a view from Index: ' . $arg . '<br />';
    }
}