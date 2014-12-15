<?php
class Core_View_Layout_Base {
    private $_output = array();
    private $_blocks = array();
    private static $_instance;

    private function __construct(){}

    public static function getInstance(){
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function addBlock($block) {
        if($block) {
            $this->_blocks[] = $block;
            return true;
        }
        return false;
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
            $currentBlock = $attributes['name'];
            $this->_blocks[$currentBlock] = App::getBlock($attributes['type']);
            $this->_blocks[$currentBlock]->_name = $currentBlock;
            $this->_blocks[$currentBlock]->_template = $attributes['template'];

            # Referencing the current block to its parent
            if($parent) {
                $this->_blocks[$currentBlock]->_parent = $parent;
            }

            if($xmlIterator->hasChildren()) {
                $this->loadBlocks($xmlIterator->block[$i],$currentBlock);
            }
            $i++;
        }
    }

    public function getBlock($name) {
        if(array_key_exists($name, $this->_blocks)) {
            return($this->_blocks[$name]);
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via getBlock(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;

    }
}