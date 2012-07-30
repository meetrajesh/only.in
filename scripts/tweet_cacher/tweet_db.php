<?php

define('ALTERNATE_DBNAME', 'twitcache');

if (file_exists('./init.local.php')) {
    dirname(__FILE__) . '/../../init.local.php';
}
require dirname(__FILE__) . '/../../lib/db.php';

class tweet_db extends db {
    
    public static function insert_tweet($user, $tweet_text, $link, $content_url, $id) {
        $stamp = time();
        $user = self::real_escape($user);
        $link = self::real_escape($link);
        $content_url = self::real_escape($content_url);
        
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

}
