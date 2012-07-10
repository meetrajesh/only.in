<?php

class PostController extends BaseController {

    public function add() {

        if (!empty($_POST['content']) || !empty($_POST['title']) || !empty($_FILES['photo']['tmp_name'])) {
            $_FILES['photo'] = !empty($_FILES['photo']) ? $_FILES['photo'] : array();
            // create the subin if it doesn't exist
            $subin_id = subin::create_subin_when_non_existing($_POST['place'], session::cuser_id());
            post::add($subin_id, session::cuser_id(), $_POST['title'], $_POST['content'], $_FILES['photo'], 0);
        }
        $slug = subin::slug_from_subin_id($subin_id);
        $this->_redirect(empty($slug) ? '/' : '/' . $slug . '/latest');
    }

    public function add_comment($user_id=0, $post_id, $parent_comment_id=0, $comment) {
        comment::add($user_id, $post_id, $parent_comment_id, $comment);
    }

}