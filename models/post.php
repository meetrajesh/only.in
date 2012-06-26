<?php

class post {

    public static function add($content, $user_id=0) {
        $user_id = (int) $user_id;
        $content = trim($content);
        if (!empty($content)) {
            $sql = 'INSERT INTO posts (content, user_id, stamp) VALUES ("%s", "%d", UNIX_TIMESTAMP())';
            db::query($sql, $content, $user_id);
            return db::insert_id();
        }
    }

    public static function get_recent($limit=10) {
        $sql = 'SELECT post_id, user_id, content, stamp FROM posts ORDER BY stamp DESC LIMIT %d';
        return db::query($sql, $limit);
    }


}