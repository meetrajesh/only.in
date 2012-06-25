<?php

class csrf {

    public static function check() {
        if (empty($_REQUEST['csrf']) || !security::hmac_check(CSRF_SECRET, self::_unique(), $_REQUEST['csrf'])) {
            die('csrf check fail');
        }
    }

    public static function html() {
        $token = self::_token(self::_unique());
        return '<input type="hidden" name="csrf" value="' . hsc($token) . '">';
    }

    public static function param() {
        $token = self::_token(self::_unique());
        return 'csrf=' . urlencode($token);
    }

    private static function _unique() {
        return session::id();
    }

    private static function _token() {
        return Security::hmac_gen(CSRF_SECRET, self::_unique());
    }

    private static function _check_token($token) {
        return security::hmac_check(CSFRF_SECRET, self::_unique(), $token);
    }
}