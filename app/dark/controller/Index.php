<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/12/15
 * Time: 2:18 PM
 */

class Dark_Controller_Index extends Core_Controller_Base {

    public function indexAction() {

        if(isset($_SESSION['fb_token'])) {
            $this->redirect('/*/home/');
        } else {
            $this->redirect('/*/login');
        }
    }
}