<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/14/15
 * Time: 5:14 PM
 */

class Dark_Controller_Logout extends Core_Controller_Base {
    public function indexAction() {
        $requestData = App::getRequest()->getData();
        if(!empty($requestData['_SESSION']['access_token'])) {
            unset($_SESSION['access_token']);
        }
        $this->redirect('*');
    }
}