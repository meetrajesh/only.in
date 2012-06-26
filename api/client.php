<?php

class OnlyInAPI {

    private $_api_secret;

    public function __construct($api_secret) {
        $this->_api_secret = $api_secret;
    }

    public function call($method, $args) {
        $curl = $this->_get_curl($method, (array) $args);

        $json = curl_exec($curl);
        curl_close($curl);

        #return var_dump($json);
        return json_decode($json, true);
    }

    private function _api_key() {
        return sha1(floor(time() / 1800) . $this->_api_secret);
    }

    private function _get_curl($method, $args) {
        $curl = curl_init();
        $args['api_key'] = $this->_api_key();

        curl_setopt($curl, CURLOPT_URL, 'http://api.onlyin.com/' . trim($method));
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $args);

        return $curl;
    }

}

