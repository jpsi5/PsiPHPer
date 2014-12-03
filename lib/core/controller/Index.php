<?php
# Move to model directory
class Core_Controller_Index extends Core_Controller_Base{

    function __construct() {
        echo 'Index Controller Created! <br />';
    }

    function view($arg = false)
    {
        echo 'This is a view: ' . $arg . '<br />';
    }

    # Test action: Used to analyze the communication between the
    # controller, model, and view
    function viewAll($arg = false) {
        $a = getModel('db/base');
        $a->set($arg);
        $view = new Core_View_Base();
        $view->run();
    }

    function other() {
        echo 'This is a view from other <br/>';
    }
}