<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/12/15
 * Time: 2:18 PM
 */

class Dark_Controller_Index extends Core_Controller_Base {

    public function indexAction() {
        $request = App::getRequest()->getData();

        if(isset($request['_SESSION']['access_token'])) {
            # User is still logged in
            $this->redirect('*/home');
        }
        else if(isset($request['_GET']['code'])) {

            # User is attempting to log in
            $fbUser = App::getModel('dark/facebook/user')->getFacebookUserData();
            $dbUser = App::getModel('dark/user');

            # Store the user information if they are registering
            if(!$dbUser->exists($fbUser['id'])) {
                $dbUser->setFacebookId($fbUser['id']);
                $dbUser->setGender($fbUser['gender']);
                $dbUser->setFirstName($fbUser['first_name']);
                $dbUser->setLastName($fbUser['last_name']);
                $dbUser->setEmailAddress($fbUser['email']);
                $dbUser->setAccessToken($fbUser['access_token']);
                $dbUser->save();
            }

            $this->redirect('*/home');
        } else {
            # User is logged out or needs to register
            $this->loadLayout();
            $this->renderLayout();
        }
    }
}