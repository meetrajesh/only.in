<?php

define('API_SECRET', '95dbb8238195850');
$api = new OnlyInAPI(API_SECRET);

$username = 'raj7';
$json = $api->call('/user/username_exists', array('username' => $username));

// check for api errors, should do this after every api call, and handle error appropriately
if (!empty($json['error'])) {
    die('Only.in API ERROR: ' . htmlspecialchars($json['error']) . "\n");
}

// if no api errors, then inspect the api response payload
var_dump($json);

// create the user if it doesn't exist
$username_exists = $json['username_exists'];
if (!$username_exists) {
    // create user
    $data = array('username' => $username,
                  'password' => 'raj',
                  'name' => 'raj',
                  'email' => 'raj@raj.com');
    
    $json = $api->call('/user/create', $data);

    // check for api errors, should do this after every api call, and handle error appropriately
    if (!empty($json['error'])) {
        die('Only.in API ERROR: ' . htmlspecialchars($json['error']) . "\n");
    }

    // if no api errors, then inspect the api response payload
    var_dump($json);

    $user_id = $json['user_id'];
}




/// --------- PHP CLIENT LIBRARY ------------- ///
class OnlyInAPI {

    private $_api_secret;

    public function __construct($api_secret) {
        $this->_api_secret = $api_secret;
    }

    public function call($method, $args) {
        $curl = $this->_get_curl($method, (array) $args);

        $json = curl_exec($curl);
        curl_close($curl);

        return json_decode($json, true);
    }

    private function _api_key() {
        return sha1(floor(time() / 1800) . $this->_api_secret);
    }

    private function _get_curl($method, $args) {
        $curl = curl_init();
        $timeout = 10;
        $args['api_key'] = $this->_api_key();

        curl_setopt($curl, CURLOPT_URL, 'http://api.onlyin.com/' . trim($method));
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $args);

        return $curl;
    }

}