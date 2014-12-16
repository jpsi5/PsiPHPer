<?php
class Admin_Controller_Index extends Core_Controller_Base {

    #*******************************************#
    # Test actions                              #
    #*******************************************#
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
}