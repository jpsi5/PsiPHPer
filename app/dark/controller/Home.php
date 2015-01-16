<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/15/15
 * Time: 3:24 PM
 */

class Dark_Controller_Home extends Core_Controller_Base {
    public function indexAction() {
        if(isset ($_SESSION['fb_token'])) {
            $userFbInfo = App::getModel('dark/facebook/user')->getGraph();
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $this->redirect('*/login');
        }

    }
}