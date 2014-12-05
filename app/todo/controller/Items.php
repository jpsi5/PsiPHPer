<?php

class Todo_Controller_Items extends Core_Controller_Base {

    function __construct() {
    }

    function view($id = null,$name = null) {

        $this->set('title',$name.' - My Todo List App');
        $this->set('todo',$this->Todo_Model_Item->select($id));

    }

    function viewall() {

        $this->set('title','All Items - My Todo List App');
        $this->set('todo',$this->Todo_Model_Item->selectAll());
    }

    function add() {
        $todo = $_POST['todo'];
        $this->set('title','Success - My Todo List App');
        $this->set('todo',$this->Todo_Model_Item->query('insert into items (item_name) values (\''.$todo.'\')'));
    }

    function delete($id = null) {
        $this->set('title','Success - My Todo List App');
        $this->set('todo',$this->Todo_Model_Item->query('delete from items where id = \''.$id.'\''));
    }

    function other(){
        echo 'hey';
    }

}