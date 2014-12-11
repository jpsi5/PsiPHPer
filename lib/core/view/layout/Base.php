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

    public function loadBlocks($xmlIterator) {
        $i = 0;
        for( $xmlIterator->rewind(); $xmlIterator->valid(); $xmlIterator->next() ) {

            $attributes = array();

            #Get the attributes
            foreach ($xmlIterator->block[$i]->attributes() as $attribute => $value) {
                $attributes[$attribute] = $value->__toString();
            }

            # Add to $_blocks of the layout object
            $this->_blocks[$attributes['name']] = App::getBlock($attributes['type']);

            if($xmlIterator->hasChildren()) {
                $this->loadBlocks($xmlIterator->block[$i]);
            }
            $i++;
        }
    }
}