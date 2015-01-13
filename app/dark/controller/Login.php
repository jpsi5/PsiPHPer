<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/13/15
 * Time: 2:02 PM
 */

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

class Dark_Controller_Login extends Core_Controller_Base {
    public function indexAction() {
        FacebookSession::setDefaultApplication('375318869339641','85f888030bd5418062f728462e028f17');
        echo 'Hello';
    }
}