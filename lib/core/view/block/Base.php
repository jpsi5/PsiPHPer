<?php

abstract class Core_View_Block_Base {

    private $_name;
    private $_template;
    protected $_flags = array();
    protected $_parent;
    protected $_templateDirectory;
    protected $_coreTemplateDirectory;

    public function __construct(){
        $this->_templateDirectory = 'view' . DS . 'template' . DS;
        $this->_coreTemplateDirectory = ROOT . 'lib' . DS . 'core' . DS . 'view' . DS . 'template' . DS;
    }

    /**
     * Magic method used to retrieve property data from this class
     *
     * @param $name The name of the property being referenced
     * @return mixed The property to be returned. Returns null if the property does not exist
     */
    public function __get($name) {
        if(property_exists($this,$name)) {
            return $this->$name;
        }

        # Trigger an error if the property cannot be referenced
        App::getHelper('core/base')->triggerReferenceError($name);
        return null;
    }

    /**
     * Sets the value of a referenced property in this class
     *
     * @param $property The name of the property being referenced
     * @param $value The value to be assigned to the property
     */
    public function __set($property,$value) {

        try {
            # Establish a friendship convention for this class and Core_View_Block_Layout
            # The only class that should have access to private members is
            # Core_View_Layout_Base
            $callingClass = App::getHelper('core/base')->getCallingClass();
            if($callingClass != 'Core_View_Layout_Base'){
                $class = get_class($this);
                throw new Exception("$class member '$property' cannot be accessed by $callingClass");
            }


            # Don't play games with the MF template
            if($property == '_template') {
                $module = App::getHelper('core/base')->getModule();
                $template = $this->getTemplateFilePath($module,$value);
                if(file_exists($template)) {
                    $this->_template = $template;
                } else {
                    $template = $this->_coreTemplateDirectory . $value;
                    if(file_exists($template)) {
                        $this->_template = $template;
                    } else {
                        throw new Exception("Template file '$value' does not exist.");
                    }
                }
            } else {
                $this->$property = $value;
            }
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . '<br />';
            exit();
        }

    }

    /**
     * Returns the html from the template linked to the block
     *
     * @param void
     * @return void
     */
    public function getHtml() {
        if(isset($this->_template)) {
            include($this->_template);
        }
    }

    /**
     * Gets the phtml template of the child block
     *
     * @param $name The name of the child block to be retrieved
     * @return void
     */
    protected function getChildHtml($name) {
        $layout = App::getLayout('core/base');
        $childBlock = $layout->getLayoutBlock($name);

        if($childBlock->_parent == $this->_name) {
            $childBlock->getHtml();
        }
    }

    /**
     * Gets the path of the template file
     *
     * @param null $moduleName The module directory to search for the template file
     * @param null $fileName The name of the template file
     * @return string Returns the path of the template file
     */
    protected function getTemplateFilePath($moduleName = null, $fileName = null){
        $templateFilePathSuffix = $this->_templateDirectory . $fileName;
        $templateFilePath = App::getModuleDirectory($moduleName) . $templateFilePathSuffix;
        $fallbackTemplateFilePath = App::getModuleDirectory('core') . $templateFilePathSuffix;
        return file_exists($templateFilePath) ? $templateFilePath : $fallbackTemplateFilePath;
    }

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

}