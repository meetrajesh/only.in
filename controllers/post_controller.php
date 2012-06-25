<?php

class PostController extends BaseController {

    public function add() {
        if (!empty($_POST['content'])) {
            csrf::check();
            Post::add($_POST['content'], Session::current_user_id());
        }
        redirect(PATH_PREFIX);
    }

}