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
     * Gets the control flags for display functionality
     *
     * @param string $name The name of the flag to retrieve
     * @return string the value of the flag or an array of all flags if no flag name is specified
     */
    protected function getFlag($name = '') {
        $flagModel = App::getModel('core/flags');
        if(!empty($name)) {
            return $flagModel->$name;
        }

        # Return the array of flags
        return $flagModel->getData();
    }

    /**
     * Sets the control flags for display functionality
     *
     * @param string $name The name of the flag to set
     * @param string $value The value of the flag $name
     */
    protected function setFlag($name = '' ,$value = '') {
        $flagModel = App::getModel('core/flags');
        if($name && $value) {
            $flagModel->$name = $value;
        }
    }

    protected function getRequest() {
        return App::getModel('core/request');
    }

    /**
     * Redirects the current controller
     *
     * @param string $url The url used to redirect the browser
     * @throws Exception when the url is invalid
     */
    protected function redirect($url) {

        # Clean up the url
        $cleanedUrl = ltrim($url, '/');
        $cleanedUrl = rtrim($cleanedUrl, '/');

        # Rules each route must follow
        $validRoutes = array(
            'mcai'  => '/^([\w]+|\*?)\/([\w]+|\*?)\/([\w]+|\*?)(\/([\d]+))+$/',
            'mca'   => '/^([\w]+|\*?)\/([\w]+|\*?)\/([\w]+|\*?)$/',
            'mc'    => '/^([\w]+|\*?)\/([\w]+|\*?)$/',
            'm'     => '/^([\w]+|\*)$/'
        );

        # Verify the format of the format of the url
        $validUrl = false;
        foreach($validRoutes as $validRoute) {
            if(preg_match($validRoute,$cleanedUrl)) {
                $validUrl = true;
                break;
            }
        }

        if($validUrl){
            # Split the url to evaluate it by parts
            $redirectUrl = array();
            $urlArray = explode('/',$cleanedUrl);
            foreach ($urlArray as $key => $urlPart) {
                if($urlPart == '*') {
                    switch($key) {
                        case 0:
                            $redirectUrl[] = App::getHelper()->getModule();
                            break;
                        case 1:
                            $redirectUrl[] = $this->_name;
                            break;
                        case 2:
                            $redirectUrl[] = strtolower(App::getHelper()->getCallingMethodName());
                            break;
                        #TODO: Use a default case to get query string values from the requested url
                    }
                }
                else {
                    $redirectUrl[] = strtolower($urlPart);
                }
            }

            # Reconstruct the url
            $redirectUrl = implode('/',$redirectUrl);

            # Redirect to the requested url
            header('Location: /' . $redirectUrl);
        }
        else {
            throw new Exception("In method " . get_class($this) . "::redirect(): '" . $url . "' is not a valid url.");
        }

    }

    /**
     * Generates the core or module's layout xml object
     *
     * @param void
     * @return void
     */
    final protected function loadLayoutOld() {

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
                $customLayoutXmlNode = $config->layout->$layoutHandle;
                $defaultLayoutXmlNode = $config->layout->default;

                # Search for the module specific layout, then fall back to default if
                # it can't be found. If neither are present, fall back to core layout
                if ($customLayoutXmlNode) {
                    $blocks = new SimpleXMLIterator($customLayoutXmlNode->asXML());
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

    final protected function loadLayout() {
        # Get help quick
        $helper = App::getHelper();

        # Get the calling action method name
        $this->_action = $helper->getCallingMethodName();

        # Build the layout handle
        $layoutHandle = $this->_module . '_' . $this->_name . '_' . $this->_action;

        # Get the config file
        $config = $helper->getConfig($this->_module);

        # Generate the layout XML
        # Validate the existence of the config file before building
        # the layout object
        if($config) {
            $layout = App::getLayout('core/base');
            $customLayoutXmlNode = $config->layout->$layoutHandle;
            $defaultLayoutXmlNode = $config->layout->default;

            if($customLayoutXmlNode && $defaultLayoutXmlNode) {
                $finalLayoutXmlNode = $layout->mergeBlocks($defaultLayoutXmlNode->block,$customLayoutXmlNode);
                $finalLayoutXmlStr = '<default>' . $finalLayoutXmlNode->asXML() . '</default>';
                $blocks = new SimpleXMLIterator($finalLayoutXmlStr);
                $blocks = new SimpleXMLIterator($blocks->asXML());
            } else if ($customLayoutXmlNode) {
                $blocks = new SimpleXMLIterator($customLayoutXmlNode->asXML());
            } else if ($defaultLayoutXmlNode) {
                $blocks = new SimpleXMLIterator($defaultLayoutXmlNode->asXML());
            }

            # Load the blocks
            $layout->loadBlocks($blocks);
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