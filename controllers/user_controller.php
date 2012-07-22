<?php

class UserController extends BaseController {

    public function logout() {
        session::logout();
        $this->_redirect('/');
    }

    public function login() {

        if (isset($_POST['btn_submit'])) {
            if (!session::validate_login($_POST['username'], $_POST['password'])) {
                $this->_errors[] = 'Invalid username/password combination';
            } else {
                $this->_redirect('/');
            }
        }

        $this->_render('login');
    }

    public function signup() {
        
        if (isset($_POST['btn_submit'])) {

            // check existing username
            if (user::does_username_exist($_POST['username'])) {
                // username already exists
                $this->_errors[] = 'Username already in use. Please select a different username';
            }

            // check existing email address
            if (user::does_email_exist($_POST['email'])) {
                // email already exists
                $this->_errors[] = 'Email already in use. Please select a different email address';
            }

            // validate email address
            if (!preg_match('/[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i', $_POST['email'])) {
                $this->_errors[] = 'Not a valid email address';
            }

            if (empty($this->_errors)) {
                // all good, insert the user
                $user_id = user::create($_POST['username'], $_POST['password'], $_POST['name'], $_POST['email']);
                if (ctype_digit((string) $user_id)) {
                    // success
                    session::login($user_id);
                    $this->_redirect('/');
                } else {
                    // user add failure
                    $this->_errors[] = 'Something went wrong with signup';
                    return false;
                }
            }

        }

        $this->_render('signup');

    }

}