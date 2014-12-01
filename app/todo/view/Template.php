<?php
class Todo_View_Template extends Core_View_Template{

    # Display template
    function render() {
        extract($this->variables);

        # Header
        if (file_exists(ROOT  . 'app' . DS . 'todo' . DS . 'view' . DS . $this->controller . DS . 'header.php')) {
            include (ROOT  . 'app' . DS . 'todo' . DS . 'view' . DS . $this->controller . DS . 'header.php');
        } else {
            //include (ROOT . 'app' . DS . 'views' . DS . 'header.php');
        }

        # Content
        include (ROOT . 'app' . DS . 'todo' . DS . 'view' . DS . $this->controller . DS . $this->action . '.php');

        # Footer
        if (file_exists(ROOT  . 'app' . DS . 'todo' . DS . 'view' . DS . $this->controller . DS . 'footer.php')) {
            include (ROOT  . 'app' . DS . 'todo' . DS . 'view' . DS . $this->controller . DS . 'footer.php');
        } else {
            //include (ROOT . DS . 'application' . DS . 'views' . DS . 'footer.php');
        }
    }

}