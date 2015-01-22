<?php
/**
 * Created by PhpStorm.
 * User: jsimon
 * Date: 1/22/15
 * Time: 9:07 AM
 */

class Dark_Model_Facebook_User extends Core_Model_Singleton {

    private $requestData;

    protected function _init() {
        $this->requestData = App::getRequest()->getData();
    }

    protected function sendCurlRequest($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    protected function getUserAccessToken() {
        # Used to store the values in the returned query string
        $params = array();

        # When the code param is received, it has to be exchanged for an
        # access token using an endpoint. This URL is used to confirm the
        # users identity by exchanging the code var for an access token
        $oauthEndpoint = "https://graph.facebook.com/oauth/access_token?".
            "client_id=". APP_ID .
            "&redirect_uri=http://psiphper.dev/dark-baggage/".
            "&client_secret=" . APP_SECRET .
            "&code=" . $this->requestData['_GET']['code'];

        # Send the cURL request
        $response = $this->sendCurlRequest($oauthEndpoint);

        # Evaluate the OAuth response string and retrieve the user access token
        # from the response string
        $tempParams = explode('&',$response);
        foreach($tempParams as $tp) {
            $tempParam = explode('=',$tp);
            $params[$tempParam[0]] = $tempParam[1];
        }

        return $params['access_token'];
    }

    protected function getAppAccessToken() {
        # App access tokens are used to make requests to
        # Facebook APIs on behalf of an app rather than a user.
        # This request URL is used to retrieve an app access token
        $appAccessTokenReqUrl = "https://graph.facebook.com/oauth/access_token?".
            "client_id=". APP_ID .
            "&client_secret=" . APP_SECRET .
            "&grant_type=client_credentials";

        # Send the cURL request
        return $this->sendCurlRequest($appAccessTokenReqUrl);
    }

    public function getFacebookUserData() {

        $userAccessToken = $this->getUserAccessToken();

        # Request URL for graph api to inspect the tokens
        $graphApiEndpoint = "https://graph.facebook.com/debug_token?".
            "input_token=". $userAccessToken . "&". $this->getAppAccessToken();

        # Request url for retrieving Facebook user data
        $userDataRequestUrl = "https://graph.facebook.com/me?".
            "access_token=". $userAccessToken;

        # Send the cURL request
        $response = $this->sendCurlRequest($graphApiEndpoint);

        # Verify that the response from the the Graph API Endpoint is valid
        $graphApiData = json_decode($response,true);

        if(empty($graphApiData['data']['app_id']) && empty($graphApiData['data']['user_id'])) {
            return false;
        } else {

            # Store the access token
            App::getRequest()->setSession('access_token',$userAccessToken);

            # Return the facebook user data
            $userData = json_decode($this->sendCurlRequest($userDataRequestUrl),true);
            return $userData;

        }
    }


}