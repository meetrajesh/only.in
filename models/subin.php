<?php

class subin {

    public static function create($subin_name, $user_id) {
        $sql = 'INSERT INTO subins (name, slug, $user_id) VALUES ("%s", "%s", %d)';
        db::query($sql, $subin_name, self::_slug_from_name($subin_name), (int) $user_id);
        return db::insert_id();
    }

    public static function create_subin_when_non_existing($subin_name) {
        $sql = 'SELECT subin_id FROM subins WHERE LOWER(name)="%s"';
        if ($subin_id = db::result_query($sql, strtolower($subin_name))) {
            return $subin_id;
        }
        return self::create($subin_name);
    }

    public static function get_subin_from_name($subin_name) {
        $sql = 'SELECT subin_id, name FROM subins WHERE slug="%s"';
        return db::fetch_query($sql, $subin_name);
    }

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

    public static function name_from_slug($slug) {
        $slug = urldecode($slug);
        $slug = str_replace('-', ' ', $slug);
        return ucwords($slug);
    }

}