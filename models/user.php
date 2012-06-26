<?php

class user {

    public static function add($data) {

        // check if username already exists
        if (self::does_username_exist($data['username'])) { // || self::does_email_exist($data['email'])) {
            return 'username ' . (string) $data['username'] . ' already exists!';
        }

        // generate the salt and hashed password
        $salt = security::gen_user_salt();
        $password = self::hash_password($salt, $data['password']);

        $sql = 'INSERT INTO users (username, password, salt, name, email, stamp, last_login_at) VALUES ("%s", "%s", "%s", "%s", "%s", %d, %d)';
        db::query($sql, $data['username'], $password, $salt, $data['name'], $data['email'], time(), time());

        return db::insert_id();

    }

    public static function mark_fake($user_id) {
        db::query('UPDATE users SET is_fake_user=1 WHERE user_id=%d', (int) $user_id);
        return true;
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
        return db::result_query('SELECT is_fake_user FROM users WHERE username="%s"', $username);
    }
}