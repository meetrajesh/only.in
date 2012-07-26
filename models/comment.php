<?php

class comment {

    public static function add($user_id=0, $post_id, $parent_comment_id=0, $comment, $stamp=0) {
        $comment = trim($comment);
        if (empty($comment)) {
            return false;
        }
        $stamp = empty($stamp) ? time() : (int)$stamp;
        $sql = 'INSERT INTO comments (user_id, post_id, parent_id, content, ip, stamp) VALUES (%d, %d, %d, "%s", "%s", %d)';
        db::query($sql, (int)$user_id, (int)$post_id, (int)$parent_comment_id, $comment, session::get_ip(), $stamp);
        return db::insert_id();
    }

    public static function get_all($post_id) {
        $sql = 'SELECT c.comment_id, c.user_id, u.username, c.content, c.stamp FROM comments c LEFT JOIN users u USING (user_id) WHERE post_id=%d ORDER BY stamp DESC';
        return db::fetch_all($sql, (int)$post_id);
    }

}