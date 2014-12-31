<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 12/22/14
 * Time: 11:34 AM
 */

class Contact_Controller_Customer extends Core_Controller_Base {

    #*******************************************#
    # CREATE                                    #
    #*******************************************#
    public function createAction() {

        if (!empty($_POST)) {

            # Keep track post values
            $params = $this->getRequest()->getParams();
            $customer = App::getModel('contact/customer');
            $customer->setMobile($params['mobile']);
            $customer->setName($params['name']);
            $customer->setEmail($params['email']);

            # Validate input
            $valid = true;
            if (empty($params['name'])) {
                $this->setFlag('nameError','Please enter Name');
                $valid = false;
            }

            if (empty($params['email'])) {
                $this->setFlag('emailError','Please enter Email Address');
                $valid = false;
            } else if ( !filter_var($customer->getEmail(),FILTER_VALIDATE_EMAIL) ) {
                $this->setFlag('emailError','Please enter a valid Email Address');
                $valid = false;
            }

            if (empty($params['mobile'])) {
                $this->setFlag('mobileError','Please enter Mobile Number');
                $valid = false;
            }

            # Insert data
            if ($valid) {
                $customer->save();
                $this->redirect('contact');
            }
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    #*******************************************#
    # READ                                      #
    #*******************************************#
    public function readAction($customerId = false) {
        $params = $this->getRequest()->getParams();
        $customer = App::getModel('contact/customer');
        $customer->load($customerId);
        $this->loadLayout();
        $this->renderLayout();
    }

    #*******************************************#
    # UPDATE                                    #
    #*******************************************#
    public function updateAction($customerId = false) {

        $customer = App::getModel('contact/customer');
        $customer->load($customerId);

        if(!empty($_POST)) {
            # Keep track post values
            $params = $this->getRequest()->getParams();
            $customer->setMobile($params['mobile']);
            $customer->setName($params['name']);
            $customer->setEmail($params['email']);

            # Validate input
            $valid = true;
            if (empty($params['name'])) {
                $this->setFlag('nameError','Please enter Name');
                $valid = false;
            }

            if (empty($params['email'])) {
                $this->setFlag('emailError','Please enter Email Address');
                $valid = false;
            } else if ( !filter_var($customer->getEmail(),FILTER_VALIDATE_EMAIL) ) {
                $this->setFlag('emailError','Please enter a valid Email Address');
                $valid = false;
            }

            if (empty($params['mobile'])) {
                $this->setFlag('mobileError','Please enter Mobile Number');
                $valid = false;
            }

            if($valid) {
                $customer->save();
                $this->redirect('contact');
            }
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    #*******************************************#
    # DELETE                                    #
    #*******************************************#
    public function deleteAction($customerId = false) {
        $customer = App::getModel('contact/customer');
        $customer->load($customerId);

        if(!empty($_POST)) {
            $customer->delete();
            $this->redirect('contact');
        } else {
            $this->loadLayout();
            $this->renderLayout();
        }
    }
}
