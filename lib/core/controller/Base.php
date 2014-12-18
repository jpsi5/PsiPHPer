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

    /**
     * Default action method of all modules
     *
     * @param void
     * @return void
     */
    public function indexAction() {}

    /**
     * Generates the core or module's layout xml object
     *
     * @param void
     * @return void
     */
    final protected function loadLayout() {

        # Get help quick
        $helper = App::getHelper();

        # Get the calling action method name
        $this->_action = $helper->getCallingMethodName();

        # Build the layout handle
        $layoutHandle = $this->_module . '_' . $this->_name . '_' . $this->_action;

        # Get the config file
        $config = $helper->getConfig($this->_module);

        # Generate layout XML
        try {

            # Validate the existence of the config file before building
            # the layout object
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

            } else {
                throw new Exception('Layout retrieval failed');
            }
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . '<br />';
        }
    }

    /**
     * Displays the view for the given controller
     *
     * @param void
     * @return void
     */
    final protected function renderLayout() {
        $layout = App::getLayout('core/base');
        $rootBlock = $layout->getLayoutBlock('root');
        $rootBlock->getHtml();
    }


}