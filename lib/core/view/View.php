<?php
# Move to view directory
class Core_View_View {

	function __construct() {
		echo 'this is the view<br/>';
	}

	public function render($name)
	{
		require 'view/' . $name . '.php';
	}
}