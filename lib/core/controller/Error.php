<?php
# Move to model directory
class Core_Controller_Error extends Core_Controller_Base{

    function __construct() {
        echo 'This is an error <br />';
    }

    function view($arg = false) {
        echo 'This is a message from Error: ' . $arg . '<br />';
        var_dump($arg);
    }
}