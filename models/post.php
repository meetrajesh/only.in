<?php

class post {

    private static $_confidences;

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
    
    public static function exists($post_id) {
        $post_id = (int) $post_id;
        if (empty($post_id)) {
            return false;
        }
        return db::has_row('SELECT null FROM posts WHERE post_id=%d AND is_deleted=0', (int)$post_id);
    }

    public static function get_popular($subin_id=0, $page=1, $limit=10) {
        return self::_get_tab_posts('popular', $subin_id, $page, $limit);
    }

    public static function get_debated($subin_id=0, $page=1, $limit=10) {
        return self::_get_tab_posts('debated', $subin_id, $page, $limit);
    }

    public static function get_top($subin_id=0, $page=1, $limit=10) {
        return self::_get_tab_posts('top', $subin_id, $page, $limit);
    }

    private static function _get_tab_posts($tab, $subin_id=0, $page=1, $limit=10) {

        $page = (int)$page;
        $limit = (int)$limit;

        // set defaults
        $page = $page > 0 ? $page : 1;
        $limit = $limit > 0 ? $limit : 10;

        $result = self::get_latest($subin_id, 0, 1, $page*$limit*3);

        // set the rank for each post
        $rank_func = "_calc_${tab}_rank";
        foreach ($result as $i => $row) {
            $result[$i]['rank'] = self::$rank_func($row);
        }

        // reverse sort the posts based on rank
        usort($result, function($post1, $post2) {
                if ($post2['rank'] != $post1['rank']) {
                    return strcmp($post2['rank'], $post1['rank']);
                } else {
                    return strcmp($post2['stamp'], $post1['stamp']);
                }
            });

        // grab the top n posts
        return array_slice($result, ($page-1)*$limit, $limit);
    }
    
    private static function _calc_popular_rank($post) {
        // from reddit popular sort: https://github.com/reddit/reddit/blob/master/r2/r2/lib/db/_sorts.pyx
        $s = $post['score'];
        $order = log(max(abs($s), 1), 10);
        $sign = ($s == 0) ? 0 : abs($s) / $s;
        $secs = $post['stamp'] - 1134028003;
        return round($order + ($sign * $secs / 45000), 7);
    }

    private static function _calc_debated_rank($post) {
        // from reddit controversy sort: https://github.com/reddit/reddit/blob/master/r2/r2/lib/db/_sorts.pyx
        return ($post['ups'] + $post['downs']) / max(abs($post['score']), 1);
    }

    private static function _calc_top_rank($post) {
        // from reddit top sort: https://github.com/reddit/reddit/blob/master/r2/r2/lib/db/_sorts.pyx
        $up_range = 400;
        $down_range = 100;

        // initialize confidences
        if (!isset(self::$_confidences)) {
            self::$_confidences = array();
            foreach (range(0, $up_range) as $ups) {
                foreach (range(0, $down_range) as $downs) {
                    self::$_confidences[] = self::_calc_confidence($ups, $downs);
                }
            }
        }

        $ups = $post['ups'];
        $downs = $post['downs'];

        if (($ups + $downs) == 0) {
            return 0;
        } elseif ($ups <= $up_range && $downs <= $down_range) {
            return self::$_confidences[$downs + $ups*$down_range];
        } else {
            return self::_calc_confidence($ups, $downs);
        }
    }

    private static function _calc_confidence($ups, $downs) {
        // The confidence sort. from http://www.evanmiller.org/how-not-to-sort-by-average-rating.html"""
        $n = $ups + $downs;

        if ($n == 0) {
            return 0;
        }

        $z = 1.281551565545; // 80% confidence
        $p = $ups / $n;
        $left = $p + 1/(2*$n)*$z*$z;
        $right = $z*sqrt($p*(1-$p)/$n + $z*$z/(4*$n*$n));
        $under = 1+1/$n*$z*$z;

        return ($left - $right) / $under;
    }

    public static function get_latest($subin_id=0, $post_id=0, $page=1, $limit=10) {
        // sanitize input
        $subin_id = (int)$subin_id;
        $page = (int)$page;
        $limit = (int)$limit;

        // set defaults
        $page = $page > 0 ? $page : 1;
        $limit = $limit > 0 ? $limit : 10;

        $where_clause  = !empty($post_id) ? 'p.post_id=%d AND ' : '(%d OR 1) AND ';
        $where_clause .= !empty($subin_id) ? 'p.subin_id=%d' : '(%d OR 1)';

        $sql = 'SELECT p.post_id, p.user_id, p.title, p.content, p.img_url, p.stamp, s.name AS subin_name, s.slug, u.username,
                       (SELECT COUNT(*) FROM comments c WHERE c.post_id=p.post_id) AS num_comments,
                       IFNULL(SUM(v.vote), 0) AS score, IFNULL(SUM(IF(v.vote=1, 1, 0)), 0) AS ups, IFNULL(SUM(IF(v.vote=-1, 1, 0)), 0) AS downs
                FROM posts p
                LEFT JOIN subins s USING (subin_id)
                LEFT JOIN votes v USING (post_id)
                LEFT JOIN users u ON p.user_id=u.user_id
                WHERE  ' . $where_clause . ' AND p.is_deleted=0
                GROUP BY p.post_id
                ORDER BY stamp DESC
                LIMIT %d, %d';

        # can't use fetch_all() since it is only available as mysqlnd (nd=native driver)
        # return $result->fetch_all(MYSQLI_ASSOC);
        $result = db::fetch_all($sql, $post_id, $subin_id, ($page-1)*$limit, $limit);

        // augment the result set with a permalink for each post
        foreach ($result as $i => $row) {
            $result[$i]['permalink'] = self::_get_permalink($row);
        }

        return $result;

    }

    private static function _get_permalink($post) {
        return sprintf('/%s/%d', $post['subin_name'], $post['post_id']);
    }

    public static function delete_by_img_url($img_url) {
        db::query('UPDATE posts SET is_deleted=1 WHERE img_url="%s"', $img_url);
        return db::affected_rows() > 0;
    }

}