<?php

class PostController extends BaseController {

    public function add() {

        if (!empty($_POST['content']) || !empty($_FILES['photo']['tmp_name'])) {
            $_FILES['photo'] = !empty($_FILES['photo']) ? $_FILES['photo'] : array();
            post::add(0, session::cuser_id(), $_POST['content'], $_FILES['photo']);
        }
        $this->_redirect('/');
    }

    public function view($subin_name) {
        if ($subin = subin::get_subin_from_name($subin_name)) {
            // subin does exist
            die('this is viewing subin name = ' . $subin['name']);
            $data = post::get_recent($subin_id);
        } else {
            // subin does not exist, but pretend like it does
            die('viewing subin name = ' . subin::name_from_slug($subin_name));
        }
    }

}