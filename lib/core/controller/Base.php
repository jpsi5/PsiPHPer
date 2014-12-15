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

    final protected function loadLayout(/* possible args */) {

        # Get help quick
        $helper = App::getHelper('core/base');

        # Get the calling action method name
        $this->_action = $this->getCallingMethodName();

        # Build the layout handle
        $layoutHandle = $this->_module . '_' . $this->_name . '_' . $this->_action;

        # Validate the config file exists
        $config = $helper->getConfig($this->_module);

        # Generate layout XML
        try {
            if ($config) {
                $layout = App::getLayout('core/base');
                $layoutXmlNode = $config->layout->$layoutHandle;
                $defaultLayoutXmlNode = $config->layout->default;

                # Search for the module specific layout, then fall back to default if
                # it can't be found. If neither are present, fall back to core layout
                if ($layoutXmlNode) {
                    $blocks = new SimpleXMLIterator($layoutXmlNode->asXML());
                } else if ($defaultLayoutXmlNode) {
                    $blocks = new SimpleXMLIterator($defaultLayoutXmlNode->asXML());
                } else {
                    $baseConfig = $helper->getConfig('core');
                    $coreLayoutXmlNode = $baseConfig->layout->default;
                    $blocks = new SimpleXMLIterator($coreLayoutXmlNode->asXML());
                }

                # Load the blocks
                $layout->loadBlocks($blocks);
                echo "<pre>";
                print_r($layout);
                echo "</pre>";

            } else {
                throw new Exception('Layout retrieval failed');
            }

        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . '<br />';
        }
    }

    protected function renderLayout() {
        $layout = App::getLayout('core/base');
        $rootBlock = $layout->getBlock('root');
        $rootBlock->getHtml();
    }

    protected function getCallingMethodName() {
        $e = new Exception();
        $trace = $e->getTrace();
        $last_call = $trace[2];
        $func = $last_call['function'];
        $actionName = strtolower(str_replace('Action','',$func));
        return $actionName;
    }
}