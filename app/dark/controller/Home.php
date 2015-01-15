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
            $currentUser = App::getModel('dark/Facebook_User')->getGraph();
            $logout = 'http://psiphper.dev/dark-baggage/logout';
            echo 'Hello '. $currentUser->getName() . '<br>';
            echo "<a href='" . $logout . "'><button>Logout</button><br>";
            echo "<a href='/dark-baggage/login/'><button>Back</button>";
        } else {
            $this->redirect('*/login');
        }

    }
}