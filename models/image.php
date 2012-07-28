<?php

class image {

    public static function is_youtube_url($url) {
        return preg_match('~https?://(www\.)?youtu\.?be(\.com)?/~i', $url);
    }

    // does this site implement the og tag?
    public static function implements_og_tag($url) {
        static $og_tag_sites = array('https?://www.flickr.com/photos/', 'http://instagram.com/p/');
        foreach ($og_tag_sites as $og_tag_site) {
            if (preg_match("~^${og_tag_site}~i", $url)) {
                return true;
            }
        }
        return false;
    }

    // parse flickr and instagram photo urls from the og:image meta tag
    public static function scrape_og_tag($url) {
        preg_match('~<meta property="og:image" content="(.+)" /?>~i', file_get_contents($url), $match);
        return $match[1];
    }

    // upload either a file upload or image url to imgur and return the imgurl raw json output and imgur thumbnail url
    public static function upload_img($photo, $is_url=false) {

        $data = !$is_url && is_array($photo) && !empty($photo['tmp_name']) ? base64_encode(file_get_contents($photo['tmp_name'])) : strip_tags($photo);

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