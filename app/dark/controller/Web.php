<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/16/15
 * Time: 11:47 AM
 */

class Dark_Controller_Web extends Core_Controller_Base{
    public function indexAction() {
        $requestData = App::getRequest()->getData();
        if($requestData['_SESSION']['access_token']) {
            $this->loadLayout();
            $this->renderLayout();
        } else {
            $this->redirect('*');
        }
    }
}