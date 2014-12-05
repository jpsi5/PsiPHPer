<?php
# Move to model directory
class Core_Controller_Error extends Core_Controller_Base{

    public function __construct() {
        header('Location: ' . ROOT . '404.php');
    }

    public function view($arg = false) {
        echo 'This is a message from Error: ' . $arg . '<br />';
        var_dump($arg);
    }
}