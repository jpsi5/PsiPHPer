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

        # Get the session
        if(isset($_SESSION['fb_token'])) {
            $this->session = new FacebookSession($_SESSION['fb_token']);
        } else {
            $this->session = $this->fbHelper->getSessionFromRedirect();
        }

        # Send the request for the login information
        if(isset($this->session)) {
            $_SESSION['fb_token'] = $this->session->getToken();
            $request = new FacebookRequest($this->session, 'GET', '/me');
            $response = $request->execute();
            $this->graph = $response->getGraphObject(GraphUser::className());

            # Getting a model for the user
            $user = App::getModel('dark/user');

            # Add the user to the database it's the first time the used
            # the application.
            if(!$user->exists($this->graph->getId())) {
                $user->setFacebookId($this->graph->getId());
                $user->setGender($this->graph->getGender());
                $user->setFirstName($this->graph->getFirstName());
                $user->setLastName($this->graph->getLastName());
                $user->setEmailAddress($this->graph->getProperty('email'));
                $user->save();
            }

            # You don't have to go home but you gotta get the f*** out of here
            $this->redirect('*/home');
        } else {
            if(!isset($_SESSION['db_redirect'])) {
                $_SESSION['db_redirect'] = true;
                header('Location: ' . $this->fbHelper->getLoginUrl(array('email')));
            }
            else {
                unset($_SESSION['db_redirect']);
                $this->redirect('*/login');
            }

        }
    }
}