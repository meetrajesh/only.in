<?php

ini_set('html_errors', false);

class OnlyInAPI {

    private $_api_secret;

    public function __construct($api_secret) {
        $this->_api_secret = $api_secret;
    }

    public function call($method, $args) {

        if (defined('IS_DEV') && IS_DEV) {
            return api::call($method, $args);
        } else {
            $curl = $this->_get_curl($method, (array) $args);
            $json = curl_exec($curl);
            curl_close($curl);
            return json_decode($json, true);
        }

    }

    private function _get_curl($method, $args) {
        $curl = curl_init();
        $args['api_key'] = api_key($this->_api_secret);
        $method = ltrim($method, '/');

        curl_setopt($curl, CURLOPT_URL, API_BASE_URL . '/' . trim($method));
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $args);

        return $curl;
    }

}

