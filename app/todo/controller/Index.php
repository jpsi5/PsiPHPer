<?php
# Move to model directory
class Todo_Controller_Index extends Core_Controller_Base{

    function __construct() {}

    #*******************************************#
    # Test actions                              #
    #*******************************************#
    function viewAction($arg = false)
    {
        echo 'This is a view: ' . $arg . '<br />';
    }

    function otherAction() {
        echo 'This is a view from other <br/>';
    }

    function indexAction() {
        echo 'Todo List: Welcome to the index page!';
    }
}