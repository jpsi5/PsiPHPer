<?php
class Core_View_Layout_Base {
    private $_blocks = array();
    private static $_instance;

    private function __construct(){}

    /**
     * Returns the singleton instance of this class
     *
     * @param void
     * @return Core_View_Layout_Base Return a singleton instance
     */
    public static function getInstance(){
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Uses the SimpleXMLIterator layout object to instantiate a block object
     * for each block it finds in the layout node of an xml config file
     *
     * @param $xmlIterator The the layout object that will be traversed
     * @param bool $parent The name of the parent block
     */
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

    /**
     * Gets the block specified by $name
     *
     * @param $name The name of the block to be retrieved
     * @return Core_View_Block_[Name] Returns the block object if found in the array
     * @return null Returns null if a block cannot be found
     */
    public function getLayoutBlock($name) {
        if(array_key_exists($name, $this->_blocks)) {
            return($this->_blocks[$name]);
        }

        # Trigger an error if the property cannot be referenced
        App::getHelper('core/base')->triggerReferenceError($name);
        return null;
    }
}