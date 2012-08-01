<?php

require 'tweetdb.local.php';

class tweet_db {
    
    private static $db;
    public static $num_queries = 0;    
    
    public static function insert_tweet($user, $tweet_text, $link, $content_url, $id) {
        $stamp = time();
        $user = self::get()->real_escape_string($user);
        $link = self::get()->real_escape_string($link);
        $content_url = self::get()->real_escape_string($content_url);
        
        $state = 0; // Unposted state
        
        # Debug
        echo "User $user" . PHP_EOL;
        echo "Content $tweet_text" . PHP_EOL;
        echo "Link $link" . PHP_EOL;
        echo "Image URL $content_url" . PHP_EOL;
        
        $sql = 'INSERT INTO tweets (stamp, user, content, id, state, content_url) VALUES (%d, "%s", "%s", %d, %d, "%s")';
        self::query($sql, (int)$stamp, $user, $tweet_text, (int)$id, (int)$state, $content_url);        
    }
    
    # TODO: Get rid of this hack
    public static function get_tweet_db() {
        return self::get();
    }

    public static function query($sql, $args=array()) {
        $db = self::get();
        $args = func_get_args();
        $sql = array_shift($args);
        $sql = self::_bind_args($sql, $args);
        # echo $sql;
        // increment the db query count
        self::$num_queries++;
        $result = $db->query($sql);
        if (!$result) {
            error('SQL error: ' . $db->error);
        }
        return $result;
    }
    
    function error($msg) {
        die($msg);
    }

    private static function _bind_args($sql, $args) {
        $db = self::get();
        if (isset($args[0])) {
            if (is_array($args[0])) {
                $args = $args[0];
            }
            foreach ($args as $key => $val) {
                $args[$key] = $db->escape_string($val);
            }
        }
        if (count($args) > 0) {
            $sql = vsprintf($sql, $args);
        }
        return $sql;
    }
    
    // singleton getter for db connection
    private static function get() {
        if (!self::$db) {
            self::$db = new mysqli(TWEET_DBHOST, TWEET_DBUSER, TWEET_DBPASS, TWEET_DBNAME);
            if (!self::$db) {
                echo('failed to obtain db connection');
            }
            self::$db->set_charset('utf8');
        }
        return self::$db;
    }    
    
}
