<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/16/15
 * Time: 11:46 AM
 */

class Dark_Controller_Connect extends Core_Controller_Base {
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
}