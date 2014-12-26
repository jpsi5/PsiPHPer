<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 12/23/14
 * Time: 2:01 PM
 */
class Contact_View_Block_Customer extends Core_View_Block_Base {

    public function getLoadedCustomer() {
        return $this->_getCustomer();
    }

    protected function _getCustomer() {
        $customers = App::getModel('contact/customer')->getData();
        return $customers;

    }

    public function getAllLoadedCustomers() {
        return $this->_getAllCustomers();
    }

    protected function _getAllCustomers() {
        $customers = App::getModel('contact/customer')->selectAll();
        return $customers;

    }
}