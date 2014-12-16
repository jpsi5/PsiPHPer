<?php

abstract class Core_View_Block_Base {

    private $_name;
    private $_template;
    private $_parent;

    public function __set($property,$value) {

        try {
            # Establish a friendship convention for this class and Core_View_Block_Layout
            # The only class that should have access to private members is
            # Core_View_Layout_Base
            $e = new Exception();
            $trace = $e->getTrace();
            $callingClass = $trace[1]['class'];
            if($callingClass != 'Core_View_Layout_Base'){
                $class = get_class($this);
                throw new Exception("$class member '$property' cannot be accessed by $callingClass");
            }


            # Don't play games with the MF template
            if($property == '_template') {
                $helper = App::getHelper('core/base');
                $module = $helper->getModule();
                $template = ROOT . 'app/' . $module . DS . 'view/template/' . $value;
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

    public function getHtml() {
        include($this->_template);
    }

    protected function getChildHtml($name) {
        $layout = App::getLayout('core/base');
        $childBlock = $layout->getLayoutBlock($name);

        if($childBlock->_parent == $this->_name) {
            $childBlock->getHtml();
        }
    }

    protected function getAbsoluteFooter() {}

}