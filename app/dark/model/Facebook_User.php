<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/14/15
 * Time: 9:48 AM
 */

# Sorry, we have to use namespaces
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;

class Dark_Model_Facebook_User extends Core_Model_Singleton {

    private $app_id = '375318869339641';
    private $app_secret = '85f888030bd5418062f728462e028f17';
    private $session;
    private $graph;

    protected function _init() {
        if(isset($_SESSION['fb_token'])) {
            FacebookSession::setDefaultApplication($this->app_id,$this->app_secret);
            $this->session = new FacebookSession($_SESSION['fb_token']);
            $request = new FacebookRequest($this->session, 'GET', '/me');
            $response = $request->execute();
            $this->graph = $response->getGraphObject(GraphUser::className());
        }
    }

    public function getGraph() {
        return $this->graph;
    }
}