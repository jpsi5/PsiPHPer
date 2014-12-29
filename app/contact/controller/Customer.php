<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 12/22/14
 * Time: 11:34 AM
 */

class Contact_Controller_Customer extends Core_Controller_Base {

    public function createAction() {

        if (!empty($_POST)) {

            # Keep track post values
            $customer = App::getModel('contact/customer');
            $customer->setMobile($_POST['mobile']);
            $customer->setName($_POST['name']);
            $customer->setEmail($_POST['email']);

            # Validate input
            $valid = true;
            if (empty($_POST['name'])) {
                $this->setFlag('nameError','Please enter Name');
                $valid = false;
            }

            if (empty($_POST['email'])) {
                $this->setFlag('emailError','Please enter Email Address');
                $valid = false;
            } else if ( !filter_var($customer->getEmail(),FILTER_VALIDATE_EMAIL) ) {
                $this->setFlag('emailError','Please enter a valid Email Address');
                $valid = false;
            }

            if (empty($_POST['mobile'])) {
                $this->setFlag('mobileError','Please enter Mobile Number');
                $valid = false;
            }

            # Insert data
            if ($valid) {
                $customer->save();
                $customer->disconnect();
                header("Location: /contact");
            }
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function readAction($customerId = false) {
        $customer = App::getModel('contact/customer');
        $customer->load($customerId);
        $this->loadLayout();
        $this->renderLayout();
    }

    public function updateAction($customerId = false) {

        $customer = App::getModel('contact/customer');
        $customer->load($customerId);

        if(!empty($_POST)) {
            $customer->setName($_POST['name']);
            $customer->setEmail($_POST['email']);
            $customer->setMobile($_POST['mobile']);
            $customer->save();
            header("Location: /contact");
        }
        else {
            $this->loadLayout();
            $this->renderLayout();
        }
    }

    public function deleteAction($customerId = false) {
        $customer = App::getModel('contact/customer');
        $customer->load($customerId);

        if(!empty($_POST)) {
            $customer->delete();
            header("Location: /contact");
        } else {
            $this->loadLayout();
            $this->renderLayout();
        }
    }
}
