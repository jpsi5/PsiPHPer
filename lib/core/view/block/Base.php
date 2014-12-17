<?php

abstract class Core_View_Block_Base {

    private $_name;
    private $_template;
    private $_parent;
    protected $_templateDirectory;

    public function __construct(){
        $this->_templateDirectory = 'view' . DS . 'template' . DS;
    }

    public function __get($name) {
        if(property_exists($this,$name)) {
            return $this->$name;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

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
                    $template = ROOT . 'lib/core/view/template/' . $value;
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

    public function getHtml() {
        if(isset($this->_template)) {
            include($this->_template);
        }
    }

    protected function getChildHtml($name) {
        $layout = App::getLayout('core/base');
        $childBlock = $layout->getLayoutBlock($name);

        if($childBlock->_parent == $this->_name) {
            $childBlock->getHtml();
        }
    }

    protected function getTemplateFilePath($moduleName = null, $fileName = null){
        $templateFilePathSuffix = DS . $this->_templateDirectory . $fileName;
        $templateFilePath = App::getModuleDirectory($moduleName) . $templateFilePathSuffix;
        $fallbackTemplateFilePath = App::getModuleDirectory('core') . $templateFilePathSuffix;
        return file_exists($templateFilePath) ? $templateFilePath : $fallbackTemplateFilePath;
    }
}