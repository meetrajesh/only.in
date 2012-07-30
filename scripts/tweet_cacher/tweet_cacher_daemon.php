<?php

require dirname(__FILE__) . '/../../models/image.php';
require dirname(__FILE__) .'/lib/tmhOAuth/tmhOAuth.php';
require dirname(__FILE__) .'/lib/tmhOAuth/tmhUtilities.php';

require 'tweet_db.php';
require dirname(__FILE__) . '/../../init.php';

date_default_timezone_set('America/Toronto');

function my_streaming_callback($data, $length, $metrics) {
    $data = json_decode($data, true);
    $tweet_text = str_replace(PHP_EOL, '', $data['text']);
    $user = $data['user']['screen_name'];

    $has_link = (strpos($tweet_text,'http') !== false);
    $is_retweet = (strpos($tweet_text,'RT @') !== false);
    $onlyin_username = (strpos(strtolower($user), 'onlyin_') !== false) || (strpos(strtolower($user), 'onlyinnycdotnet') !== false);
    
    if ($has_link && !$onlyin_username) {
        # Format tweet for HTML output
        $link = get_link_from_text($tweet_text);
        $tweet_text = make_links_clickable($tweet_text);
        $content_url = get_content_url($link);
        $id = $data['id'];
        
        tweet_db::insert_tweet($user, $tweet_text, $link, $content_url, $id);
    }
}

function make_links_clickable($text) {
    if ($text) {
        $text = trim($text);
        while ($text != stripslashes($text)) { $text = stripslashes($text); }    
        $text = strip_tags($text,"<b><i><u>");
        $text = preg_replace("/(?<!http:\/\/)www\./","http://www.",$text);
        $text = preg_replace( "/((http|ftp)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\" target=\"_blank\">\\0</a>",$text);   
    }
    return $text;
}

function get_content_url($url) {
    if ($url) {
        return image::scrape_og_tag($url);
    }
}

function get_link_from_text($text) {
    // Check if there is a url in the text
    if(preg_match('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $text, $url)) {
        #echo "URL " . $url[0] . PHP_EOL;
        return $url[0];
    } else {
        #echo "No URL" . PHP_EOL;
    }
}

$tmhOAuth = new tmhOAuth(array(
  'consumer_key'    => 'Mt2NDCK8DUn5BtElaa8zPA',
  'consumer_secret' => 'mcaj6x7dWTwjk9r9xKG2O5BHe36yfLbVwiQDGClpUR8',
  'user_token'      => '618886497-sFdxlRJSRGXXd1yZh5GkUIEwllwpdwcg4LqnNs1F',
  'user_secret'     => 'djDR9n5ZPTbb2mEWrXALtiUHAujFg7ug938rEiM',
));

$method = 'https://stream.twitter.com/1/statuses/filter.json';
$track = "onlyin,onlyinamerica,onlyinusa,onlyinwashington";
$track = $track . ',onlyinlondon,onlyinla,onlyinchicago,onlyinny,onlyinnyc,onlyinnewyork,onlyintoronto,onlyinatlanta';
$track = $track . ',onlyinsf,onlyinsanfran,onlyinthecity,onlyinsanfrancisco,onlyinboston,onlyinseattle,onlyinredmond,onlyinsydney';
$track = $track . ',onlyincanada,onlyinbrazil,onlyinuk,onlyingermany,onlyinindia,onlyinjapan,onlyinaustralia';

$params = array();
$params['track'] = $track;
$tmhOAuth->streaming_request('POST', $method, $params, 'my_streaming_callback');

?>
