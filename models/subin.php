<?php

class subin {

    public static $RESERVED_SUBINS = array('404', 'tos', 'contact', 'place', 'places', 'popular', 'latest', 'debated', 'top', 'post', 'admin', 'user', 'subin', 'subins');

    public static function create($subin_name, $user_id=0) {
        $subin_name = trim($subin_name);

        // check if the subin exists first
        $sql = 'SELECT subin_id FROM subins WHERE LOWER(name) = LOWER("%s")';
        if ($subin_id = db::result_query($sql, strtolower($subin_name))) {
            // return subin_id if subin already exists
            return $subin_id;
        }

        // perform checks on subin name and length
        if (strlen($subin_name) < SUBIN_MIN_LEN) {
            return 'subin name too short!';
        } elseif (self::_is_reserved_subin($subin_name)) {
            return 'invalid/reserved subin name';
        }

        // strip numerals from subin name
        $subin_name = preg_replace('/\d/', '', $subin_name);

        // capitalize the subin name
        $subin_name = self::format_subin_name($subin_name);

        $sql = 'INSERT INTO subins (name, slug, user_id, stamp) VALUES ("%s", "%s", %d, %d)';
        db::query($sql, $subin_name, slug_from_name($subin_name), (int) $user_id, time());
        return db::insert_id();
    }

    private static function _is_reserved_subin($name) {
        return in_array(strtolower($name), self::$RESERVED_SUBINS);
    }

    // lookup subin from slug in db
    public static function slug_to_subin($subin_name) {
        $sql = 'SELECT subin_id, slug, name FROM subins WHERE LOWER(slug) = LOWER("%s")';
        return db::fetch_query($sql, $subin_name);
    }

    public static function slug_from_subin_id($subin_id) {
        $sql = 'SELECT slug FROM subins WHERE subin_id=%d';
        return db::result_query($sql, $subin_id);
    }

    // reverse operation of previous function
    public static function slug_to_name($slug) {
        $slug = urldecode($slug);
        $slug = str_replace('-', ' ', $slug);
        return self::format_subin_name($slug);
    }

    public static function format_subin_name($subin_name) {
        return strlen($subin_name) <= 3 ? strtoupper($subin_name) : ucwords($subin_name);
    }

    public static function get_popular($num_days=1, $limit=10) {
        $num_days = (int) $num_days;
        $limit = (int) $limit;

        // return the list of subins posted to the most in the last 10 days
        $sql = 'SELECT s.subin_id, s.slug, s.name, (SELECT COUNT(*) FROM posts p WHERE s.subin_id=p.subin_id AND p.stamp > (UNIX_TIMESTAMP() - %d*86400)) AS num_posts FROM subins s
                HAVING num_posts > 0
                ORDER BY num_posts DESC
                LIMIT %d';

        $result = db::fetch_all($sql, $num_days, $limit);

        // augment with subin url
        foreach ($result as &$row) {
            $row['permalink'] = '/' . $row['slug'];
            $row['name'] = self::format_subin_name($row['name']);
            unset($row['slug']);
        }
        return $result;
    }

    public static function all_places() {
        $results = db::fetch_all('SELECT s.subin_id, s.slug AS permalink, s.name, (SELECT COUNT(*) FROM posts p WHERE s.subin_id=p.subin_id) AS num_posts FROM subins s HAVING num_posts > 0 ORDER BY s.name ASC');
        foreach ($results as $i => $row) {
            $results[$i]['name'] = self::format_subin_name($row['name']);
        }
        return $results;
    }

    public static function search($str) {
        $results = db::fetch_all('SELECT s.subin_id, s.slug AS permalink, s.name, (SELECT COUNT(*) FROM posts p WHERE s.subin_id=p.subin_id) AS num_posts FROM subins s WHERE name LIKE "%s%%" OR slug LIKE "%1$s%%" HAVING num_posts > 0 ORDER BY s.name ASC LIMIT 10', $str);
        foreach ($results as $i => $row) {
            $results[$i]['name'] = self::format_subin_name($ow['name']);
        }
        return $results;
    }

}