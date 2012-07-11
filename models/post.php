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
            list($imgur_raw_json, $img_url) = self::_upload_img($photo, false);
        } elseif (preg_match('~^https?://.+\.(png|jpg|jpeg|gif)$~iU', $content)) {
            list($imgur_raw_json, $img_url) = self::_upload_img($content, true);
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

    private static function _upload_img($photo, $is_url=false) {

        $data = !$is_url && is_array($photo) && !empty($photo['tmp_name']) ? base64_encode(file_get_contents($photo['tmp_name'])) : $photo;

        // $data is file data
        $pvars = array('image' => $data, 'key' => IMGUR_API_KEY);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'http://api.imgur.com/2/upload.json');
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);

        $raw_json = curl_exec($curl);

        // delete the uploaded tmp file if it exists, may not exist if file was uploaded by url
        if (!$is_url && !empty($photo['tmp_name']) && file_exists($photo['tmp_name'])) {
            unlink($photo['tmp_name']);
        }

        $json = json_decode($raw_json, true);

        if (false === $raw_json || is_null($json)) {
            var_dump($raw_json);
            $error = curl_error($curl);
            curl_close($curl);
            die($error);
        }

        // handle imgur failure
        if (is_null($json) || empty($json['upload']['links']['large_thumbnail'])) {
            return array((string) $raw_json, '');
        }

        return array($raw_json, $json['upload']['links']['large_thumbnail']);

    }

}