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
            # Keep track validation errors
            $nameError = null;
            $emailError = null;
            $mobileError = null;

            # Keep track post values
            $name = $_POST['name'];
            $email = $_POST['email'];
            $mobile = $_POST['mobile'];

            # Validate input
            $valid = true;
            if (empty($name)) {
                $nameError = 'Please enter Name';
                $valid = false;
            }

            if (empty($email)) {
                $emailError = 'Please enter Email Address';
                $valid = false;
            } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
                $emailError = 'Please enter a valid Email Address';
                $valid = false;
            }

            if (empty($mobile)) {
                $mobileError = 'Please enter Mobile Number';
                $valid = false;
            }

            # Insert data
            if ($valid) {
                $customer = App::getModel('contact/customer');
                $customer->insert($name,$email,$mobile);
                $customer->disconnect();
                header("Location: /contact");
            }
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    public function updateAction() {}

    public function deleteAction() {}
}