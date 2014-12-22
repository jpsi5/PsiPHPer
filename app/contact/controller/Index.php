<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 12/22/14
 * Time: 10:21 AM
 */

class Contact_Controller_Index extends Core_Controller_Base {

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
}