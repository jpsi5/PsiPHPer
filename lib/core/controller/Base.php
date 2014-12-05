<?php
# Move to controller directory
abstract Class Core_Controller_Base {

    protected $template;

	public function __construct() {}

    public function __call($name, $arguments) {

//        # Get the config file for the working module
//        $helper = App::getHelper('core/base');
//        $module = array_shift(explode('_',get_class($this)));
//        $config = $helper->getConfig($module);
//
//        if($config->bad_request->dir) {
//            # Generate this view if bad_request is set in config.xml
//            //$view = ucfirst($module) . '_View_' . ucfirst($config->bad_request->dir);
//            echo 'Custom View For Bad Request <br/>';
//        }
//        else {
//            #Default view to generate
//            echo 'Default View For Bad Url Request: ' . $name . '<br/>';
//        }
        header('Location: ' . ROOT . '404.php');
    }

    public function indexAction() {
    }
}