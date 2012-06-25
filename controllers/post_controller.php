<?php

class PostController extends BaseController {

    public function add() {
        if (!empty($_POST['content'])) {
            csrf::check();
            post::add($_POST['content'], session::cuser_id());
        }
        redirect(PATH_PREFIX);
    }

}