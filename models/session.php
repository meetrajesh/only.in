<?php

class session {

    private static $_current_user;

    public static function id() {
        return session_id();
    }

    public static function current_user_id() {
        return self::$_current_user ? self::$_current_user->user_id : 0;
    }

}
