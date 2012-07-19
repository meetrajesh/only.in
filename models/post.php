<?php

class post {

    public static function add($subin_id, $user_id=0, $title='', $content, $photo=array(), $stamp=0) {

        $subin_id = (int) $subin_id;
        $user_id = (int) $user_id;
        $title = trim($title);
        $content = trim($content);
        $stamp = (!empty($stamp) && $stamp > 0) ? (int) $stamp : time();
        $img_url = '';
        $imgur_raw_json = '';

        if (!empty($photo['tmp_name'])) {
            list($imgur_raw_json, $img_url) = image::upload_img($photo, false);
        } elseif (preg_match('~^https?://.+\.(png|jpg|jpeg|gif)$~iU', $content)) {
            list($imgur_raw_json, $img_url) = image::upload_img($content, true);
        } elseif (preg_match('~^https?://www.flickr.com/photos/.+~i', $content)) {
            list($imgur_raw_json, $img_url) = image::upload_img(image::get_flickr_url($content), true);
        }

        if (strlen($title . $content . $img_url) > 0) {
            $sql = 'INSERT INTO posts (subin_id, user_id, title, content, img_url, imgur_raw_json, stamp) VALUES ("%d", "%d", "%s", "%s", "%s", "%s", %d)';
            db::query($sql, $subin_id, $user_id, $title, $content, $img_url, $imgur_raw_json, $stamp);
            return db::insert_id();
        }

    }

    public static function get_popular($subin_id=0, $page=1, $limit=10) {
        $result = self::get_latest($subin_id, 1, $page*$limit*3);

        // set the rank for each post
        foreach ($result as $i => $row) {
            $result[$i]['rank'] = self::_calc_rank($row);
        }

        // reverse sort the posts based on rank
        usort($result, function($post1, $post2) {
                if ($post2['rank'] != $post1['rank']) {
                    return strcmp($post2['rank'], $post1['rank']);
                } else {
                    return strcmp($post2['stamp'], $post1['stamp']);
                }
            });

        return array_slice($result, ($page-1)*$limit, $limit);
    }
    
    private static function _calc_rank($post) {
        $s = $post['score'];
        $order = log(max(abs($s), 1), 10);
        $sign = ($s == 0) ? 0 : abs($s) / $s;
        $secs = $post['stamp'] - 1134028003;
        return round($order + ($sign * $secs / 45000), 7);
    }

    public static function get_latest($subin_id=0, $page=1, $limit=10) {
        // sanitize input
        $subin_id = (int)$subin_id;
        $page = (int)$page;
        $limit = (int)$limit;

        // set defaults
        $page = $page > 0 ? $page : 1;
        $limit = $limit > 0 ? $limit : 10;

        $where_clause = !empty($subin_id) ? 'subin_id=%d' : '1';
        $sql = 'SELECT p.post_id, p.user_id, p.title, p.content, p.img_url, p.stamp, IFNULL(SUM(v.vote), 0) AS score, s.name AS subin_name 
                FROM posts p
                INNER JOIN subins s USING (subin_id)
                LEFT JOIN votes v ON p.post_id=v.post_id 
                WHERE  ' . $where_clause . ' AND p.is_deleted=0
                GROUP BY p.post_id
                ORDER BY stamp DESC
                LIMIT %d, %d';
        $result = !empty($subin_id) ? db::query($sql, $subin_id, ($page-1)*$limit, $limit) : db::query($sql, ($page-1)*$limit, $limit);
        # can't use fetch_all() since it is only available as mysqlnd (nd=native driver)
        # return $result->fetch_all(MYSQLI_ASSOC);
        return db::fetch_all($result);
    }

    public static function delete_by_img_url($img_url) {
        db::query('UPDATE posts SET is_deleted=1 WHERE img_url="%s"', $img_url);
        return db::affected_rows() > 0;
    }

}