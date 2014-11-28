<?php
class Todo_Model_Template extends Core_Model_Template{

    # Display template
    function render() {
        extract($this->variables);

        if (file_exists(ROOT  . 'app' . DS . 'todo' . DS . 'view' . DS . $this->controller . DS . 'header.php')) {
            include (ROOT  . 'app' . DS . 'todo' . DS . 'view' . DS . $this->controller . DS . 'header.php');
        } else {
            //include (ROOT . DS . 'application' . DS . 'views' . DS . 'header.php');
        }

        include (ROOT . 'app' . DS . 'todo' . DS . 'view' . DS . $this->controller . DS . $this->action . '.php');

        if (file_exists(ROOT  . 'app' . DS . 'todo' . DS . 'view' . DS . $this->controller . DS . 'footer.php')) {
            include (ROOT  . 'app' . DS . 'todo' . DS . 'view' . DS . $this->controller . DS . 'footer.php');
        } else {
            //include (ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php');
        }
    }

}