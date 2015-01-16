<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/16/15
 * Time: 11:47 AM
 */

class Dark_Controller_Web extends Core_Controller_Base{
    public function indexAction() {
        if(isset ($_SESSION['fb_token'])) {
            $currentUser = App::getModel('dark/facebook/user')->getGraph();
            $logout = 'http://psiphper.dev/dark-baggage/logout';
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $this->redirect('*/login');
        }
    }
}