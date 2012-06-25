<?php

class user {

    public static function add($data) {

        // generate the salt and hashed password
        $salt = security::gen_user_salt();
        $password = self::hash_password($salt, $_POST['password']);

        $sql = 'INSERT INTO users (username, password, salt, name, email, stamp, last_login_at) VALUES ("%s", "%s", "%s", "%s", "%s", %d, %d)';
        db::query($sql, $_POST['username'], $password, $salt, $_POST['name'], $_POST['email'], time(), time());

        return db::insert_id();

    }

    public static function hash_password($salt, $password) {
        return security::hmac_gen(USER_PWD_SECRET, $salt . $password);
    }

    public static function does_username_exist($username) {
        return db::has_row('SELECT null FROM users WHERE username = "%s"', $username);
    }

    public static function does_email_exist($email) {
        return db::has_row('SELECT null FROM users WHERE email = "%s"', $email);
    }
}