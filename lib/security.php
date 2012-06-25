<?php

class security {

    public static function hmac_gen($secret, $data) {
        return hash_hmac('sha256', $data, $secret);
    }

    public static function hmac_check($secret, $data, $provided) {
        return self::hmac_gen($secret, $data) == $provided;
    }

}