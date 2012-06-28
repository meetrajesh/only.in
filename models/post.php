<?php

class post {

    public static function add($subin_id, $user_id=0, $content, $photo=array(), $stamp=0) {

        $subin_id = (int) $subin_id;
        $user_id = (int) $user_id;
        $content = trim($content);
        $stamp = (!empty($stamp) && $stamp > 0) ? (int) $stamp : time();
        $img_url = '';
        $imgur_raw_json = '';

        if (!empty($photo['tmp_name'])) {
            list($imgur_raw_json, $img_url) = self::_upload_img($photo, false);
        } elseif (preg_match('~^https?://.+\.(png|jpg|jpeg|gif)$~iU', $content)) {
            list($imgur_raw_json, $img_url) = self::_upload_img($content, true);
        }

        if (strlen($content . $img_url) > 0) {
            $sql = 'INSERT INTO posts (user_id, content, img_url, imgur_raw_json, stamp) VALUES ("%d", "%s", "%s", "%s", %d)';
            db::query($sql, $user_id, $content, $img_url, $imgur_raw_json, $stamp);
            return db::insert_id();
        }

    }

    public static function get_recent($subin_id=0, $limit=10) {
        $where_clause = !empty($subin_id) ? 'WHERE subin_id=%d' : '';
        $sql = 'SELECT post_id, user_id, content, img_url, stamp FROM posts ' . $where_clause . ' WHERE is_deleted=0 ORDER BY stamp DESC LIMIT %d';
        return !empty($subin_id) ? db::query($sql, $subin_id, $limit) : db::query($sql, $limit);
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