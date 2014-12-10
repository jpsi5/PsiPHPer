<?php
class Todo_Controller_Index extends Core_Controller_Base{

    #*******************************************#
    # Test actions                              #
    #*******************************************#
    function viewAction($arg = false)
    {
        echo 'This is a view: ' . $arg . '<br />';
        $this->loadLayout();
    }

    function otherAction() {
        echo 'This is a view from other <br/>';
    }

    function indexAction() {
        echo 'Todo List: Welcome to the index page!';
        $this->loadLayout();
    }
}