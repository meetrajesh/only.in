<?php

class image {

    // parse the flickr photo url from the og:image meta tag
    public static function get_flickr_url($url) {
        preg_match('~<meta property="og:image" content="(http://farm.+\.jpg)" />~', file_get_contents($url), $match);
        return $match[1];
    }

    // upload either a file upload or image url to imgur and return the imgurl raw json output and imgur thumbnail url
   public static function upload_img($photo, $is_url=false) {

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
        if (is_null($json) || isset($json['error']) || empty($json['upload']['links']['large_thumbnail'])) {
            return array((string) $raw_json, '');
        }

        return array($raw_json, $json['upload']['links']['large_thumbnail']);

    }

}