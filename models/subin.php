<?php

class subin {

    public static function create($subin_name, $user_id=0) {
        if (strlen($subin_name) < SUBIN_MIN_LEN) {
            die('subin name too short!');
        }
        $sql = 'INSERT INTO subins (name, slug, user_id) VALUES ("%s", "%s", %d)';
        db::query($sql, $subin_name, self::_slug_from_name($subin_name), (int) $user_id);
        return db::insert_id();
    }

    public static function create_subin_when_non_existing($subin_name, $user_id=0) {
        $sql = 'SELECT subin_id FROM subins WHERE LOWER(name)="%s"';
        if ($subin_id = db::result_query($sql, strtolower($subin_name))) {
            return $subin_id;
        }
        return self::create($subin_name, $user_id);
    }

    // lookup subin from slug in db
    public static function slug_to_subin($subin_name) {
        $sql = 'SELECT subin_id, name FROM subins WHERE slug="%s"';
        return db::fetch_query($sql, $subin_name);
    }

    public static function slug_from_subin_id($subin_id) {
        $sql = 'SELECT slug FROM subins WHERE subin_id=%d';
        return db::result_query($sql, $subin_id);
    }

    // convert subin name to slug 
    private static function _slug_from_name($subin_name) {

        $subin_name = trim(strtolower($subin_name));
        // get rid of funky chars
        $subin_name = str_replace(array("\r", "\n", "\t"), '', $subin_name);
        // replace sequence of white-space or underscores with hyphen
        $subin_name = preg_replace('/[\s_]+/', '-', $subin_name);
        // get rid of all non-word chars
        $subin_name = preg_replace('/[^\w-]/', '', $subin_name);
        // replace underscores with hyphens
        $subin_name = str_replace('_', '-', $subin_name);
        // replace multiple hyphens with single hyphen
        $subin_name = preg_replace('/-+/', '-', $subin_name);

        return $subin_name;
    }

    // reverse operation of previous function
    public static function slug_to_name($slug) {
        $slug = urldecode($slug);
        $slug = str_replace('-', ' ', $slug);
        return ucwords($slug);
    }

}