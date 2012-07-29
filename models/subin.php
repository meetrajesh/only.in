<?php

class subin {

    public static function create($subin_name, $user_id=0) {
        if (strlen($subin_name) < SUBIN_MIN_LEN) {
            die('subin name too short!');
        }
        $sql = 'INSERT INTO subins (name, slug, user_id) VALUES ("%s", "%s", %d)';
        db::query($sql, $subin_name, slug_from_name($subin_name), (int) $user_id);
        return db::insert_id();
    }

    public static function create_subin_when_non_existing($subin_name, $user_id=0) {
        // check if the subin exists first
        $sql = 'SELECT subin_id FROM subins WHERE LOWER(name) = LOWER("%s")';
        if ($subin_id = db::result_query($sql, strtolower($subin_name))) {
            // return subin_id if subin already exists
            return $subin_id;
        }
        // create the subin since it doesn't exist
        return self::create($subin_name, $user_id);
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
        return ucwords($slug);
    }

    public static function get_popular($num_days=10, $limit=10) {
        $num_days = (int) $num_days;
        $limit = (int) $limit;

        // return the list of subins post to the most in the last 7 days
        $sql = 'SELECT s.subin_id, s.slug, s.name, (SELECT COUNT(*) FROM posts p WHERE s.subin_id=p.subin_id AND p.stamp > (UNIX_TIMESTAMP() - %d*86400)) AS num_posts FROM subins s
                HAVING num_posts > 0
                ORDER BY num_posts DESC
                LIMIT %d';

        $result = db::fetch_all($sql, $num_days, $limit);

        // sort the results by name
        usort($result, function($a, $b) {
                return strcmp(strtolower($a['name']), strtolower($b['name']));
            });

        // augment with subin url
        foreach ($result as &$row) {
            $row['permalink'] = '/' . $row['slug'];
            unset($row['slug']);
        }
        return $result;
    }

    public static function all_places() {
        return db::fetch_all('SELECT s.subin_id, s.slug AS permalink, s.name, (SELECT COUNT(*) FROM posts p WHERE s.subin_id=p.subin_id) AS num_posts FROM subins s HAVING num_posts > 0 ORDER BY s.name ASC');
    }

    public static function search($str) {
        return db::fetch_all('SELECT s.subin_id, s.slug AS permalink, s.name, (SELECT COUNT(*) FROM posts p WHERE s.subin_id=p.subin_id) AS num_posts FROM subins s WHERE name LIKE "%s%%" OR slug LIKE "%1$s%%" HAVING num_posts > 0 ORDER BY s.name ASC LIMIT 10', $str);
    }

}