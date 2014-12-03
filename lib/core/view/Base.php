<?php
# Move to view directory
class Core_View_Base {

	function __construct() {
		//echo 'this is the view<br/>';
	}

	public function render($name)
	{
		require 'view/' . $name . '.php';
	}

    public function run() {
        $c = getModel('db/base');
        echo $c->get() . '<br />';
    }
}