<?php

class Db_Model_Base extends Db_Model_SQLQuery {

    private $model;

    protected function _init() {
        $className = get_class($this);
        $temp = explode('_',$className);
        $this->model = end($temp);
        $this->table = strtolower($this->model) . 's';
        $this->dbHandle = App::getModel('db/SQLConn')->getConnection();
        $this->tableInit();
        $this->primaryKey = $this->getPrimaryKeyName();
    }

    public function __call($name,$arguments) {
        # Get the method (i.e. get,set,unset...) and the property name
        # The $matches variable is an array containing a method and the property
        # name.
        $matches = preg_split('#([A-Z][^A-Z]*)#', $name , null, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $method = array_shift($matches);
        $property = '';

        # Formatting the property name of the object to match the column field
        # name format in a database
        if(count($matches) == 1) {
            $property = strtolower($matches[0]);
        } else {

            # Validate that $property is the name of a column in the table
            $property = implode('_',$matches);
            $property = $this->validColumnName(strtolower($property)) ? strtolower($property) : lcfirst(implode('',$matches));
        }

        switch ($method) {
            case 'get':
                return $this->$property;
                break;
            case 'set':
                $this->$property = !empty($arguments) ? $arguments[0] : null;
                break;
            case 'unset':
                $this->$property = null;
                break;
            case 'has':
                return array_key_exists($property, $this->data) ? true : false;
                break;
            default:
                throw new Exception('In object ' . get_class($this) . ': method \'' . $name . '\' does not exist.');
        }

        return null;
    }

    public function load($id) {
        $result = $this->select($this->primaryKey, $id);
        foreach($result[0] as $key => $resultData) {
            $this->data[$key] = $resultData;
            $this->origData[$key] = $resultData;
        }
    }

    protected function _beforeSave(){

        # Validating the data before it is save
        foreach($this->data as $colName => $attribute){
            if(empty($attribute)) {
                if($this->isRequired($colName)){

                    # Set the status if the form data is invalid;
                    App::getModel('core/request')->setParam('FORM_STATUS',INVALID_FORM_DATA);
                    return false;
                }
            }
        }
    }

    protected function _afterSave(){}

    public function save() {

        # Format column order
        $fields = $this->getColumnNames();
        $args = array();
        foreach($fields as $field) {
            $args[$field] = htmlspecialchars($this->data[$field]);
        }

        # Remove the primary key
        unset($args[$this->primaryKey]);

        if(empty($this->origData))
        {
            # Creating a new entry
            $args = implode(',',$args);
            $this->insert($args);
        } else {

            # Append the primary key to the end of the array. This
            # is done because SQLQuery::update() requires the last
            # parameter to be the column used in the WHERE clause
            $args[$this->primaryKey] = $this->data[$this->primaryKey];

            # Updating and existing entry
            $args = implode(',',$args);
            $this->update($args);
        }
    }

    public function delete() {
        $this->remove($this->data[$this->primaryKey]);
    }
}