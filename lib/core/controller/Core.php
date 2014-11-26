<?php
# Move to controller directory
abstract Class Core_Controller_Core {

	function __construct() {
		echo 'Main Controller<br />';
		//$this->view = new Core_View_View();
	}

    function viewAction() {

    }
}