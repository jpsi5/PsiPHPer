<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/14/15
 * Time: 10:42 AM
 */

class Dark_View_Block_Content extends Core_View_Block_Content {
    public function getFacebookModel() {
        return $this->_getFacebookModel();
    }

    protected function _getFacebookModel() {
        return App::getModel('dark/facebook');
    }
}