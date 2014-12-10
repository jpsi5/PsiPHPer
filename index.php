<?php

define ('DS', DIRECTORY_SEPARATOR);
define ('ROOT', realpath(dirname(__FILE__)) . DS);

require_once(ROOT . 'lib/config.php');
require_once(ROOT . 'lib/bootstrap.php');

//function GetCallingMethodName(){
//    $e = new Exception();
//    $trace = $e->getTrace();
//    //position 0 would be the line that called this function so we ignore it
//    $last_call = $trace[2];
//    echo '<br /><pre>';
//    print_r($last_call);
//    echo '</pre>';
//}
//
//function firstCall($a, $b){
//    theCall($a, $b);
//}
//
//function theCall($a, $b){
//    GetCallingMethodName();
//}
//
//firstCall('lucia', 'php');


