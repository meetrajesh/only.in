<?php

class vote {

    public static function add($user_id=0, $post_id, $comment_id, $vote) {
    
        $vote = (int) $vote;
        if (!in_array($vote, array(-1, 1))) {
            $vote = 0;
        }

        $sql = 'INSERT INTO votes (user_id, post_id, comment_id, vote, stamp) VALUES (%d, %d, %d, "%s", %d)';
        db::query($sql, $user_id, $post_id, $comment_id, $vote, time());
        return db::insert_id();
    }

}