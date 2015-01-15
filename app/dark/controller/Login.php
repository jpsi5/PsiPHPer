<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/13/15
 * Time: 2:02 PM
 */


class Dark_Controller_Login extends Core_Controller_Base {
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
}