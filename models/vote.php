<?php

class vote {

    public static function add($user_id=0, $post_id=0, $comment_id=0, $vote) {

        $user_id = (int) $user_id;
        $post_id = (int) $post_id;
        $comment_id = (int) $comment_id;
        $vote = (int) $vote;

        // check if vote is either 1 or -1
        if (!in_array($vote, array(-1, 1))) {
            $vote = 0;
        }

        $sql = 'INSERT INTO votes (user_id, post_id, comment_id, vote, stamp) VALUES (%d, %d, %d, "%s", %d)';
        db::query($sql, $user_id, $post_id, $comment_id, $vote, time());
        return db::insert_id();
    }

}