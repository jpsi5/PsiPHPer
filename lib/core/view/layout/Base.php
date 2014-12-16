<?php
class Core_View_Layout_Base {
    private $_blocks = array();
    private static $_instance;

    private function __construct(){}

    public static function getInstance(){
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function loadBlocks($xmlIterator,$parent = false) {
        $i = 0;
        for( $xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next() ) {

            $attributes = array();

            #Get the attributes
            foreach ($xmlIterator->block[$i]->attributes() as $attribute => $value) {
                $attributes[$attribute] = $value->__toString();
            }

            # Add to $_blocks of the layout object and initialize the block
            $currentBlockName = $attributes['name'];
            $this->_blocks[$currentBlockName] = App::getBlock($attributes['type']);
            $this->_blocks[$currentBlockName]->_name = $currentBlockName;
            $this->_blocks[$currentBlockName]->_template = $attributes['template'];

            # Referencing the current block to its parent
            if($parent) {
                $this->_blocks[$currentBlockName]->_parent = $parent;
            }

            # Checking the existence of child nodes
            if($xmlIterator->hasChildren()) {
                $this->loadBlocks($xmlIterator->block[$i],$currentBlockName);
            }
            $i++;
        }
    }

    public function getLayoutBlock($name) {
        if(array_key_exists($name, $this->_blocks)) {
            return($this->_blocks[$name]);
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined block via getLayoutBlock(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }
}