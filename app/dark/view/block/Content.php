<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/14/15
 * Time: 10:42 AM
 */

class Dark_View_Block_Content extends Core_View_Block_Content {
    public function getCurrentFacebookUser() {
        return $this->_getCurrentFacebookUser();
    }

    protected function _getCurrentFacebookUser() {
        return App::getModel('dark/facebook/user')->getGraph();
    }

    public function getFbImageUrl() {
        return $this->_getFbImageUrl();
    }

    protected function _getFbImageUrl(){
        $fbUserInfo = App::getModel('dark/facebook/user')->getGraph();
        $imageUrl = 'https://graph.facebook.com/' . $fbUserInfo->getId() . '/picture?width=125';
        return $imageUrl;
    }
}