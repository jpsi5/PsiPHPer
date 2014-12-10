<?php
# Move to controller directory
abstract Class Core_Controller_Base {

    private $_module;
    private $_action;
    private $_name;

	final public function __construct() {
        $class = get_class($this);
        $classNameArray = explode('_',$class);
        $this->_module = strtolower($classNameArray[0]);
        $this->_name = strtolower(end($classNameArray));
    }

    public function indexAction() {
    }

    protected function loadLayout(/* possible args */) {

        $this->_action = $this->getCallingMethodName();
        $layoutHandle = $this->_module . '_' . $this->_name . '_' . $this->_action;
        echo '<br/>'. $layoutHandle;

        # Generate layout XML

        # Instantiate a block class foreach </block> tag

        # Use tag's type attribute to get class name of the block

        # Store the block
    }

    protected function renderLayout() {}

    protected function getCallingMethodName() {
        $e = new Exception();
        $trace = $e->getTrace();
        $last_call = $trace[2];
        $func = $last_call['function'];
        $actionName = strtolower(str_replace('Action','',$func));
        return $actionName;
    }
}