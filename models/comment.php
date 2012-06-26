<?php

class comment {

    public static function add($user_id=0, $post_id, $parent_comment_id=0, $comment) {
        $sql = 'INSERT INTO comments (user_id, post_id, parent_id, content, stamp) VALUES (%d, %d, %d, "%s", %d)';
        db::query($sql, (int)$user_id, (int)$post_id, (int)$parent_comment_id, $content, time());
        return db::insert_id();
    }

}