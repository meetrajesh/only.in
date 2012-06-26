<?php

class post {

    public static function add($subin_id, $user_id=0, $content, $stamp=0) {
        $subin_id = (int) $subin_id;
        $user_id = (int) $user_id;
        $content = trim($content);
        $stamp = (!empty($stamp) && $stamp > 0) ? (int) $stamp : time();
        if (!empty($content)) {
            $sql = 'INSERT INTO posts (content, user_id, stamp) VALUES ("%s", "%d", %d)';
            db::query($sql, $content, $user_id, $stamp);
            return db::insert_id();
        }
    }

    public static function get_recent($subin_id=0, $limit=10) {
        $where_clause = !empty($subin_id) ? 'WHERE subin_id=%d' : '';
        $sql = 'SELECT post_id, user_id, content, stamp FROM posts ' . $where_clause . ' ORDER BY stamp DESC LIMIT %d';
        return !empty($subin_id) ? db::query($sql, $subin_id, $limit) : db::query($sql, $limit);
    }

}