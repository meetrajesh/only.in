<?php

class PostController extends BaseController {

    public function add() {
        Post::add($_POST['content']);
        redirect(PATH_PREFIX);
    }

}