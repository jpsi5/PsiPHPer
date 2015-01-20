<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/12/15
 * Time: 2:18 PM
 */

class Dark_Controller_Index extends Core_Controller_Base {

    public function indexAction() {
        if(isset($_GET['access_token'])) {
            $this->redirect('*/home');
        }
        else if(isset($_GET['code'])) {

            # When code is received, it has to be exchanged for an access token using an endpoint
            # This uri is use to exchange your code var for an access token
            $oauthEndpoint = "https://graph.facebook.com/oauth/access_token?".
            "client_id=375318869339641".
            "&redirect_uri=http://psiphper.dev/dark-baggage/".
            "&client_secret=85f888030bd5418062f728462e028f17".
            "&code=" . $_GET['code'];

            # Send a request to the Oauth endpoint
            $a = strtolower('HHH');
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $oauthEndpoint);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);

        } else {
            $this->loadLayout();
            $this->renderLayout();
        }
    }
}