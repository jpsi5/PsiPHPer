<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/15/15
 * Time: 3:24 PM
 */

class Dark_Controller_Home extends Core_Controller_Base {
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