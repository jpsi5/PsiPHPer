<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/12/15
 * Time: 2:18 PM
 */

class Dark_Controller_Index extends Core_Controller_Base {

    public function indexAction() {
        $params = array();
        $request = App::getRequest();
        $requestData = $request->getData();

        if(isset($requestData['_SESSION']['access_token'])) {
            $this->redirect('*/home');
        }
        else if(isset($requestData['_GET']['code'])) {
            # The login process is composed of the following steps after
            # the login dialog has  been invoked
            # ----------------------------------------------------------
            # 1. Exchange the Code parameter for an User Access Token.
            # 2. Send a request for an App Access Token.
            # 3. Use both tokens to verify that the User Access Token is
            #    is valid for the person.

            # When the code param is received, it has to be exchanged for an
            # access token using an endpoint. This uri is used to confirm the
            # users identity by exchanging the code var for an access token
            $oauthEndpoint = "https://graph.facebook.com/oauth/access_token?".
            "client_id=". APP_ID .
            "&redirect_uri=http://psiphper.dev/dark-baggage/".
            "&client_secret=" . APP_SECRET .
            "&code=" . $requestData['_GET']['code'];

            # App access tokens are used to make requests to
            # Facebook APIs on behalf of an app rather than a user.
            # This request url is used to retrieve an app access token
            $appAccessTokenReqUrl = "https://graph.facebook.com/oauth/access_token?".
            "client_id=". APP_ID .
            "&client_secret=" . APP_SECRET .
            "&grant_type=client_credentials";

            # Send a request to the Oauth endpoint to retrieve the access token
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $oauthEndpoint);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $oauthResponse = curl_exec($curl);

            # Send a request to retrieve an app access token
            curl_setopt($curl, CURLOPT_URL, $appAccessTokenReqUrl);
            $appAccessResponse = curl_exec($curl);


            # Evaluate the OAuth response string and retrieve the access token
            $tempParams = explode('&',$oauthResponse);
            foreach($tempParams as $tp) {
                $tempParam = explode('=',$tp);
                $params[$tempParam[0]] = $tempParam[1];
            }

            # Using graph api to inspect the tokens
            $graphApiEndpoint = "https://graph.facebook.com/debug_token?".
            "input_token=". $params['access_token'] . "&". $appAccessResponse;

            curl_setopt($curl, CURLOPT_URL, $graphApiEndpoint);
            $graphApiResponse = curl_exec($curl);

            # Verify that the response from the the Graph API Endpoint is valid
            $graphApiData = json_decode($graphApiResponse,true);
            if(empty($graphApiData['data']['app_id']) && empty($graphApiData['data']['user_id'])) {
                # Redirect to the login page
                $this->redirect('*');
            } else {
                # Request url for retrieving user data
                $graphApiEndpoint = "https://graph.facebook.com/me?".
                    "access_token=". $params['access_token'];

                curl_setopt($curl, CURLOPT_URL, $graphApiEndpoint);
                $graphApiResponse = curl_exec($curl);

                $userData = json_decode($graphApiResponse,true);
                curl_close($curl);

                # Store the access token
                $request->setSession('access_token', $params['access_token']);
                $user = App::getModel('dark/user');

                # Store the user information if they are not registered
                if(!$user->exists($userData['id'])){
                    $user->setFacebookId($userData['id']);
                    $user->setGender($userData['gender']);
                    $user->setFirstName($userData['first_name']);
                    $user->setLastName($userData['last_name']);
                    $user->setEmailAddress($userData['email']);
                    $user->setAccessToken($params['access_token']);
                    $user->save();
                }
                $this->redirect('*/home');
            }

        } else {
            $this->loadLayout();
            $this->renderLayout();
        }
    }
}