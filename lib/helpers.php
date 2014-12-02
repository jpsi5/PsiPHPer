<?php

function getModel($path) {
    $str = strtolower($path);
    $pathArray = explode(DS,$str);
    try {
        if(count($pathArray) < 2) {
            throw new Exception('Invalid argument. Usage: object getModel ( string $string )');
        }

        # Build the name of the model
        $model = ucfirst($pathArray[0]) . '_Model_' . ucfirst($pathArray[1]);

        # Verify that $model is a valid class name.
        if(class_exists($model)) {
            return $model::getInstance();
        }
        else {
            throw new Exception('Class [' . $model . '] not found.');
        }


    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . '<br />';
    }
}