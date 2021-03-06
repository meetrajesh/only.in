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
        db::query($sql, $user_id, $post_id, $comment_id, $vote, session::get_ip(), time());

        return db::insert_id();
    }

    // return upvotes - downvotes for given post_id
    public static function get_score($post_id) {
        $sql = 'SELECT IFNULL(SUM(vote), 0) AS score FROM votes WHERE post_id=%d';
        return db::result_query($sql, (int)$post_id);
    }

    // add a + in front of positive score, return number as comma separated
    public static function format_score($score) {
        return ($score > 0 ? '+' : '') . number_format($score);
    }

    public static function can_vote_again($post_id) {
        $ip = session::get_ip();
        if (empty($ip)) {
            return true;
        }
        return MAX_VOTES_PER_IP > db::result_query('SELECT COUNT(*) FROM votes WHERE post_id=%d AND ip="%s"', (int)$post_id, $ip);
    }

}