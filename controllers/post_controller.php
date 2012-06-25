<?php

class PostController extends BaseController {

    public function add() {
        if (!empty($_POST['content'])) {
            post::add($_POST['content'], session::cuser_id());
        }
        redirect('/');
    }

}