<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/15/15
 * Time: 10:53 AM
 */

class Dark_Model_User extends Db_Model_Base {
    public function exists($id) {
        $result = $this->select('facebook_id',$id);
        if(!empty($result)) {
            return true;
        }
        return false;
    }
}