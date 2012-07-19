<?php

class vote {

    public static function add($user_id=0, $post_id=0, $comment_id=0, $vote) {

        $user_id = (int) $user_id;
        $post_id = (int) $post_id;
        $comment_id = (int) $comment_id;
        $vote = (int) $vote;

        // check if vote is either 1 or -1
        if (abs($vote) !== 1) {
            return -1;
        }

        $sql = 'INSERT INTO votes (user_id, post_id, comment_id, vote, ip, stamp) VALUES (%d, %d, %d, "%s", "%s", %d)';
        $ip = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        db::query($sql, $user_id, $post_id, $comment_id, $vote, $ip, time());
        return db::insert_id();
    }

    public static function get_score($post_id) {
        $sql = 'SELECT IFNULL(SUM(vote), 0) AS score FROM votes WHERE post_id=%d';
        return db::result_query($sql, (int)$post_id);
    }

    public static function format_score($score) {
        return ($score > 0 ? '+' : '') . (string)$score;
    }

}