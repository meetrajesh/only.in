<?php

require dirname(__FILE__) . '/../../models/image.php';
require dirname(__FILE__) .'/lib/tmhOAuth/tmhOAuth.php';
require dirname(__FILE__) .'/lib/tmhOAuth/tmhUtilities.php';

require 'tweet_db.php';

ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', true);
ini_set('html_errors', true);

date_default_timezone_set('America/New_York');

function my_streaming_callback($data, $length, $metrics) {
    $data = json_decode($data, true);
    var_dump($data);
    $tweet_text = str_replace(PHP_EOL, '', $data['text']);
    $user = $data['user']['screen_name'];

    $has_link = (strpos($tweet_text,'http') !== false);
    $is_retweet = (strpos($tweet_text,'RT @') !== false);
    $onlyin_username = (strpos(strtolower($user), 'onlyin_') !== false) 
            || (strpos(strtolower($user), 'onlyinnycdotnet') !== false)
            || (strpos(strtolower($user), '0nlyin') !== false);
    
    if ($has_link && !$onlyin_username && !$is_retweet) {
        # Format tweet for HTML output
        $link = get_link_from_text($tweet_text);
        $content_url = get_content_url($link);
        $id = $data['id'];
        if ($content_url) {
            tweet_db::insert_tweet($user, $tweet_text, $link, $content_url, $id);
        }
    }
}

function get_content_url($url) {
    if ($url) {
        $content_url = image::scrape_og_tag($url);
        $content_url = $content_url ? $content_url : image::has_photo_url($url);
        return $content_url; // Null may be returned
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
    'consumer_key'    => TWEET_CONSUMER_KEY,
    'consumer_secret' => TWEET_CONSUMER_SECRET,
    'user_token'      => TWEET_USER_TOKEN,
    'user_secret'     => TWEET_USER_SECRET,
));

$method = 'https://stream.twitter.com/1/statuses/filter.json';
$track = "onlyin,onlyinamerica,onlyinusa,onlyinwashington";
$track = $track . ',onlyinlondon,onlyinla,onlyinchicago,onlyinny,onlyinnyc,onlyinnewyork,onlyintoronto,onlyinatlanta';
$track = $track . ',onlyinsf,onlyinsanfran,onlyinthecity,onlyinsanfrancisco,onlyinboston,onlyinseattle,onlyinredmond,onlyinsydney';
$track = $track . ',onlyincanada,onlyinbrazil,onlyinuk,onlyingermany,onlyinindia,onlyinjapan,onlyinaustralia';
#$track = $track . ',london,la,chicago,ny,nyc,newyork,toronto,atlanta,sf,sanfrancisco,boston,seattle,redmond,sydney';

$params = array();
$params['track'] = $track;

$result = $tmhOAuth->streaming_request('POST', $method, $params, 'my_streaming_callback');
if (!$result) {
  var_dump($tmhOAuth);
}

?>
