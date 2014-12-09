<?php
# Move to model directory
class Admin_Controller_Index extends Core_Controller_Base{

    #*******************************************#
    # Test actions                              #
    #*******************************************#
    public function viewAction($arg = false)
    {
        echo 'This is a view: ' . $arg . '<br />';
    }

    public function otherAction() {
        echo 'This is a view from other <br/>';
    }

    public function indexAction() {
        echo 'Admin: Welcome to the index page!';
    }
}