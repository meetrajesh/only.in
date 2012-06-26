<?php

class ApiController {

    public function user_username_exists($data) {
        return user::does_username_exist($data['username']);
    }

    public function user_is_fake_user($data) {
        return user::is_fake_user($data['username']);
    }

    public function user_create($data) {
        return user::add($data);
    }

    public function post_create($data) {
        
        $data['stamp'] = empty($data['stamp']) ? time() : (int) $data['stamp'];
        $subin_id = subin::create_subin_if_non_existing($data['subin_name']);
        $post_id = post::add($subin_id, $data['user_id'], $data['content'], $data['stamp']);

        foreach (range(1, $data['num_upvotes']) as $i) {
            vote::add($data['user_id'], $post_id, 0, 1);
        }

        foreach (range(1, $data['num_downvotes']) as $i) {
            vote::add($data['user_id'], $post_id, 0, -1);
        }

        return $post_id;
    }

    public function comment_create($data) {

        $data['stamp'] = empty($data['stamp']) ? time() : (int) $data['stamp'];
        $data['parent_comment_id'] = empty($data['parent_comment_id']) ? 0 : (int) $data['parent_comment_id'];
        
        $comment_id = comment::add($data['user_id'], $data['post_id'], $data['parent_comment_id'], $data['comment']);

        foreach (range(1, $data['num_upvotes']) as $i) {
            vote::add($data['user_id'], 0, $comment_id, 1);
        }

        foreach (range(1, $data['num_downvotes']) as $i) {
            vote::add($data['user_id'], 0, $comment_id, -1);
        }

        return $comment_id;

    }

}

