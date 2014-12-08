<?php
# Move to model directory
class Core_Controller_Error extends Core_Controller_Base{

    public function __construct() {

    }

    public function viewAction($arg = false) {
        echo '<pre> This is a message from Error: ' . $arg . '</pre><br />';
    }
}