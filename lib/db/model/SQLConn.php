<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 12/29/14
 * Time: 8:58 AM
 */

class Db_Model_SQLConn extends Core_Model_Singleton {
    protected $dbHandle;

    public function connect()
    {
        $module = App::getHelper('core/base')->getModule();
        $db = App::getHelper('core/base')->getDbCredentials($module);

        # Create the database if it does not exist
        $conn = new PDO('mysql:host=' . $db["host"] . '; dbname=' . 'mysql' .'; charset=utf8', $db["user"], $db["password"]);
        $conn->query('CREATE DATABASE IF NOT EXISTS ' . $db['name']);
        $conn = null;

        # Connect to the database
        $this->dbHandle = new PDO('mysql:host=' . $db["host"] . '; dbname=' . $db["name"] .'; charset=utf8', $db["user"], $db["password"]);
        $this->dbHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->dbHandle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function disconnect() {
        $this->dbHandle = null;
    }

    public function getConnection()  {
        try {
            if($this->dbHandle) {
                return $this->dbHandle;
            } else {
                throw new Excpetion('In method Db_Model_SQLCon::getConnection(): Connection not available');
            }
        } catch (Exception $e) {
            echo 'Caught Exception' . $e->getMessage() . '<br/>';
        }
        return null;
    }


}