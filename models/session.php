<?php

class session {

    public static function id() {
        return session_id();
    }

    public static function cuser_id() {
        return !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    }

    public static function current_username() {
        return self::logged_in() ? $_SESSION['username'] : 0;
    }

    // is there a current user logged in?
    public static function logged_in() {
        return !empty($_SESSION['user_id']);
    }

    public static function validate_login($username, $password) {
        // check if username exists
        $creds = db::fetch_query('SELECT user_id, salt, password FROM users WHERE username="%s"', trim($username));
        if (!$creds) {
            // user not found with that username
            return false;
        }
        if (user::hash_password($creds['salt'], $password) == $creds['password']) {
            session::login($creds['user_id']);
            return true;
        }
        // password mismatch
        return false;
    }

    public static function login($user_id) {
        $user_id = (int) $user_id;
        // validate the user_id
        if ($username = db::result_query('SELECT username FROM users WHERE user_id=%d', $user_id)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            db::query('UPDATE users SET last_login_at=%d WHERE user_id=%d', time(), $user_id);
            return true;
        } else {
            return false;
        }
    }

    public static function logout() {
        unset($_SESSION['user_id'], $_SESSIONM['username']);
        $_SESSION = array();
        session_destroy();
    }

}
