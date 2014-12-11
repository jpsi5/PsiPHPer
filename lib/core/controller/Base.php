<?php
# Move to controller directory
abstract Class Core_Controller_Base {

    private $_module;
    private $_action;
    private $_name;

	public function __construct() {
        $class = get_class($this);
        $classNameArray = explode('_',$class);
        $this->_module = strtolower($classNameArray[0]);
        $this->_name = strtolower(end($classNameArray));
    }

    public function indexAction() {}

    protected function loadLayout(/* possible args */) {

        # Get help quick
        $helper = App::getHelper('core/base');

        # Get the calling action method name
        $this->_action = $this->getCallingMethodName();

        # Build the layout handle
        $layoutHandle = $this->_module . '_' . $this->_name . '_' . $this->_action;

        # Validate the config file exists
        $config = $helper->getConfig($this->_module);
        $baseConfig = $helper->getConfig('core');

        # Generate layout XML
        if($config) {
            $layout = App::getLayout('core/base');
            $layoutXmlNode = $config->layout->$layoutHandle;
            $defaultLayoutXmlNode = $config->layout->default;
            if($layoutXmlNode){
                $blocks = new SimpleXMLIterator($layoutXmlNode->asXML());
                $layout->loadBlocks($blocks);
            }
            else if ($defaultLayoutXmlNode){

            }
            else {

            }
        } else {

        }

        # Instantiate a block class foreach </block> tag

        # Use tag's type attribute to get class name of the block

        # Store the block
    }

    protected function renderLayout() {}


    protected function getCallingMethodName() {
        $e = new Exception();
        $trace = $e->getTrace();
        $last_call = $trace[2];
        $func = $last_call['function'];
        $actionName = strtolower(str_replace('Action','',$func));
        return $actionName;
    }
}