<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/15/15
 * Time: 3:48 PM
 */

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;

class Dark_Controller_Register extends Core_Controller_Base {

    private $app_id = '375318869339641';
    private $app_secret = '85f888030bd5418062f728462e028f17';
    private $redirect_url = 'http://psiphper.dev/dark-baggage/register';
    private $fbHelper;
    private $session;
    private $graph;

    public function indexAction() {
        FacebookSession::setDefaultApplication($this->app_id,$this->app_secret);
        $this->fbHelper = new FacebookRedirectLoginHelper($this->redirect_url);

        if(isset($_SESSION['fb_token'])) {
            $this->session = new FacebookSession($_SESSION['fb_token']);
        } else {
            $this->session = $this->fbHelper->getSessionFromRedirect();
        }

        if(isset($this->session)) {
            $_SESSION['fb_token'] = $this->session->getToken();
            $request = new FacebookRequest($this->session, 'GET', '/me');
            $response = $request->execute();
            $this->graph = $response->getGraphObject(GraphUser::className());
            $this->redirect('*/home');
        } else {
            header('Location: ' . $this->fbHelper->getLoginUrl(array('email')));
        }
    }
}