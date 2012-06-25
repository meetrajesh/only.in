<?php

class session {

    public static function id() {
        return session_id();
    }

    public static function cuser_id() {
        return !empty($_SESSION['cuser_id']) ? $_SESSION['cuser_id'] : 0;
    }

}
