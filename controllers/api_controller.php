<?php

class ApiController {

    public function user_username_exists($data) {
        return array('username_exists' => user::does_username_exist($data['username']));
    }

    public function user_is_fake_user($data) {
        return array('is_fake_user' => user::is_fake_user($data['username']));
    }

    public function user_create($data) {

        $is_fake_user = true;
        $user_id = user::add($data['username'], $data['password'], $data['name'], $data['email'], $is_fake_user);

        if (false === $user_id || $user_id == 0 || !ctype_digit((string) $user_id)) {
            return array('error' => $user_id);
        }

        return array('user_id' => $user_id);
    }

    public function post_create($data) {

        // use the current timestamp if it doesn't exist
        $data['stamp'] = empty($data['stamp']) ? time() : (int) $data['stamp'];
        
        // get the userid from the username
        $data['user_id'] = user::getid($data['username']);

        // create the subin if it doesn't exist
        $subin_id = subin::create_subin_when_non_existing($data['subin_name'], $data['user_id']);

        // add the post to the particular subin
        $post_id = post::add($subin_id, $data['user_id'], $data['content'], $data['stamp']);

        // insert the upvotes
        for ($i=0; $i < $data['num_upvotes']; $i++) {
            vote::add($data['user_id'], $post_id, 0, 1);
        }

        // insert the downvotes
        for ($i=0; $i < $data['num_downvotes']; $i++) {
            vote::add($data['user_id'], $post_id, 0, -1);
        }

        // return the post id
        return array('post_id' => $post_id);
    }

    public function comment_create($data) {

        // use the current timestamp if it doesn't exist
        $data['stamp'] = empty($data['stamp']) ? time() : (int) $data['stamp'];
        $data['parent_comment_id'] = empty($data['parent_comment_id']) ? 0 : (int) $data['parent_comment_id'];
        
        // get the userid from the username
        $data['user_id'] = user::getid($data['username']);

        // add the comment to the given post id
        $comment_id = comment::add($data['user_id'], $data['post_id'], $data['parent_comment_id'], $data['comment']);

        // insert the upvotes
        for ($i=0; $i < $data['num_upvotes']; $i++) {
            vote::add($data['user_id'], 0, $comment_id, 1);
        }

        // insert the downvotes
        for ($i=0; $i < $data['num_downvotes']; $i++) {
            vote::add($data['user_id'], 0, $comment_id, -1);
        }

        // return the comment id
        return array('comment_id' => $comment_id);

    }

}

