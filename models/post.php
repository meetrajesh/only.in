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

    public static function get_recent($subin_id=0, $limit=10) {
        $where_clause = !empty($subin_id) ? 'WHERE subin_id=%d' : '';
        $sql = 'SELECT post_id, user_id, content, stamp FROM posts ' . $where_clause . ' ORDER BY stamp DESC LIMIT %d';
        return !empty($subin_id) ? db::query($sql, $subin_id, $limit) : db::query($sql, $limit);
    }


}