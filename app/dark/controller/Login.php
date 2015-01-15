<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/13/15
 * Time: 2:02 PM
 */

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

class Dark_Controller_Login extends Core_Controller_Base {

    private $app_id = '375318869339641';
    private $app_secret = '85f888030bd5418062f728462e028f17';
    private $redirect_url = 'http://psiphper.dev/dark-baggage/login';
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

        $logout = 'http://psiphper.dev/dark-baggage/logout';

        if(isset($this->session)) {
            $_SESSION['fb_token'] = $this->session->getToken();
            $request = new FacebookRequest($this->session, 'GET', '/me');
            $response = $request->execute();
            $this->graph = $response->getGraphObject(GraphUser::className());
            $name = $this->graph->getName();
            $id = $this->graph->getId();
            $image = 'https://graph.facebook.com/' . $id . '/picture?width=300';
            $email = $this->graph->getProperty('email');
            echo "Hi $name <br>";
            echo "your email is $email <br><br>";
            echo "<img src='$image' /><br><br>";
            echo "<a href='" . $logout . "'><button>Logout</button>";
        } else {
            header('Location: ' . $this->fbHelper->getLoginUrl(array('email')));
        }
    }
}