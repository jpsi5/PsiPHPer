<?php
# Move to model directory
class Todo_Controller_Index extends Core_Controller_Base{

    function __construct() {}

    #*******************************************#
    # Test actions                              #
    #*******************************************#
    function view($arg = false)
    {
        echo 'This is a view: ' . $arg . '<br />';
    }

    function other() {
        echo 'This is a view from other <br/>';
    }

    function index() {
        echo 'Welcome to the index page!';
    }
}