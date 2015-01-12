<?php

class Core_Controller_Event_Observer {
    public function redirectTo404(){
        App::getRequest()->redirect('404');
    }
}