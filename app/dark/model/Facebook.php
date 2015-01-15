<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/14/15
 * Time: 9:48 AM
 */

# Sorry, we have to use namespaces
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookCurl;

class Dark_Model_Facebook extends Core_Model_Singleton {

    private $app_id = '375318869339641';
    private $app_secret = '85f888030bd5418062f728462e028f17';
    private $redirect_url = 'http://psiphper.dev/dark-baggage/login';
    private $fbHelper;
    private $session;
    private $graph;

    protected function _init() {
        if(isset($_REQUEST['logout']))
        {
            unset($_SESSION['fb_token']);
        }

        FacebookSession::setDefaultApplication($this->app_id,$this->app_secret);
        $this->fbHelper = new FacebookRedirectLoginHelper($this->redirect_url);
        $this->session = $this->fbHelper->getSessionFromRedirect();

        if(isset($_SESSION['fb_token'])) {
            $this->session = new FacebookSession($_SESSION['fb_token']);
        }

        if(isset($this->session)) {
            $_SESSION['fb_token'] = $this->session->getToken();
            $request = new FacebookRequest($this->session, 'GET', '/me');
            $response = $request->execute();
            $this->graph = $response->getGraphObject(GraphUser::className());
        }
        else {
            header('Location: ' . $this->getLoginUrl());
        }
    }

    public function getLoginUrl() {
        return $this->fbHelper->getLoginUrl();
    }

    public function getGraph() {
        return $this->graph;
    }
}