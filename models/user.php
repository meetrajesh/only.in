<?php

class user {

    public static function add($username, $password, $name, $email, $is_fake = false) {

        // set up the data
        $data = array('username' => $username, 'password' => $password, 'name' => $name, 'email' => $email, 'is_fake' => $is_fake);

        // check if username already exists
        if (self::does_username_exist($data['username'])) { // || self::does_email_exist($data['email'])) {
            return 'username ' . (string) $data['username'] . ' already exists!';
        }

        // generate the salt and hashed password
        $salt = security::gen_user_salt();
        $password = self::hash_password($salt, $data['password']);

        $sql = 'INSERT INTO users (username, password, salt, name, email, stamp, last_login_at, is_fake) VALUES ("%s", "%s", "%s", "%s", "%s", %d, %d, %d)';
        db::query($sql, $data['username'], $password, $salt, $data['name'], $data['email'], time(), time(), (int)$is_fake);

        return db::insert_id();

    }

    public static function getid($username) {
        return db::result_query('SELECT user_id FROM users WHERE username="%s"', $username);
    }

    public static function hash_password($salt, $password) {
        return security::hmac_gen(USER_PWD_SECRET, $salt . $password);
    }

    public static function does_username_exist($username) {
        return db::has_row('SELECT null FROM users WHERE username="%s"', $username);
    }

    public static function does_email_exist($email) {
        return db::has_row('SELECT null FROM users WHERE email="%s"', $email);
    }

    public static function is_fake_user($username) {
        return db::result_query('SELECT is_fake FROM users WHERE username="%s"', $username);
    }
}