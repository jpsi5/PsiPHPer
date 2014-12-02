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
}