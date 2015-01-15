<?php

define ('DS', DIRECTORY_SEPARATOR);
define ('ROOT', realpath(dirname(__FILE__)) . DS);

require_once(ROOT . 'lib/config.php');
require_once(ROOT . 'lib/bootstrap.php');

//use Facebook\FacebookSession;
//use Facebook\FacebookRedirectLoginHelper;
//use Facebook\FacebookRequest;
//use Facebook\FacebookResponse;
//use Facebook\FacebookSDKException;
//use Facebook\FacebookRequestException;
//use Facebook\FacebookAuthorizationException;
//use Facebook\GraphObject;
//use Facebook\GraphUser;
//use Facebook\GraphSessionInfo;
//use Facebook\HttpClients\FacebookHttpable;
//use Facebook\HttpClients\FacebookCurlHttpClient;
//use Facebook\HttpClients\FacebookCurl;
//
//session_start();
//
//if(isset($_REQUEST['logout']))
//{
//    unset($_SESSION['fb_token']);
//}
//
//$app_id = '375318869339641';
//$app_secret = '85f888030bd5418062f728462e028f17';
//$redirect_url = 'http://psiphper.dev/dark-baggage/';
//
//FacebookSession::setDefaultApplication($app_id,$app_secret);
//$fbHelper = new FacebookRedirectLoginHelper($redirect_url);
//$session = $fbHelper->getSessionFromRedirect();
//
//if(isset($_SESSION['fb_token'])) {
//    $session = new FacebookSession($_SESSION['fb_token']);
//}
//
//$logout = 'http://psiphper.dev/dark-baggage/fblogin&logout=true';
//
//if(isset($session)) {
//    $_SESSION['fb_token'] = $session->getToken();
//    $request = new FacebookRequest($session, 'GET', '/me');
//    $response = $request->execute();
//    $graph = $response->getGraphObject(GraphUser::className());
//    $name = $graph->getName();
//    $id = $graph->getId();
//    $image = 'https://graph.facebook.com/' . $id . '/picture?width=300';
//    $email = $graph->getProperty('email');
//    echo "Hi $name <br>";
//    echo "your email is $email <br><br>";
//    echo "<img src='$image' /><br><br>";
//    echo "<a href='" . $logout . "'><button>Logout</button>";
//} else {
//    echo '<a href="' . $fbHelper->getLoginUrl(array('email')) . '" >Login with facebook</a>';
//}





