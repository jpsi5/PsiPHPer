<?php

abstract class Core_View_Block_Base {

    private $_name;
    private $_template;
    private $_parent;

    public function __set($property,$value) {
        try {
            if($property && $value) {
                # Don't play games with the MF template
                if($property == '_template') {
                    $helper = App::getHelper('core/base');
                    $module = $helper->getModule();
                    $template = ROOT . 'app/' . $module . DS . 'view/template/' . $value;
                    if(file_exists($template)) {
                        $this->_template = $template;
                    }
                    else {
                        # TODO: Change this implementation to handle fall back templates
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

    }

    public function getHtml() {
        include($this->_template);
    }

    protected function getChildHtml($name) {
        $layout = App::getLayout('core/base');
        $childBlock = $layout->getBlock($name);

        if($childBlock->_parent == $this->_name) {
            $childBlock->getHtml();
        }
    }

    protected function getAbsoluteFooter() {}

}