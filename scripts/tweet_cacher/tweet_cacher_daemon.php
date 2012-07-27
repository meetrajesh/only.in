<?php

require dirname(__FILE__) . '/../../models/image.php';
require './lib/tmhOAuth/tmhOAuth.php';
require './lib/tmhOAuth/tmhUtilities.php';

date_default_timezone_set('America/Toronto');

function my_streaming_callback($data, $length, $metrics) {
    $data = json_decode($data, true);
    $tweet = str_replace(PHP_EOL, '', $data['text']);
    $user = $data['user']['screen_name'];

    $has_link = (strpos($tweet,'http') !== false);
    $is_retweet = (strpos($tweet,'RT @') !== false);
    $onlyin_username = (strpos(strtolower($user), 'onlyin_') !== false) || (strpos(strtolower($user), 'onlyinnycdotnet') !== false);
    
    if ($has_link) {
        echo $tweet . PHP_EOL;
        echo "Is Retweet (Null/1) $is_retweet" . PHP_EOL;
        echo "Onlyin_ Username $user : $onlyin_username" . PHP_EOL . PHP_EOL;
    }
    
    if ($has_link && !$onlyin_username) {
        # Format tweet for HTML output
        $link = get_link_from_text($tweet);
        $tweet = make_links_clickable($tweet);
        $content_url = get_content_url($link);

        # TODO: put these constants somewhere
        $db = new mysqli("127.0.0.1", "root", "root", 'twitcache');

        if (!$db)
        {
            die('AB Could not connect: ' . mysql_error());
        }

        $stamp = time(); //$data['created_at'];
        $user = $db->real_escape_string($user);
        echo "User $user" . PHP_EOL;
        $content = $db->real_escape_string($tweet);
        echo "Content $content" . PHP_EOL;
        echo "Link $link" . PHP_EOL;
        echo "Image URL $content_url" . PHP_EOL;
        $id = $data[id];
        $state = 0;
        
        $sql = "INSERT INTO tweets (stamp, user, content, id, state, content_url) 
            VALUES ($stamp, \"$user\", \"$content\", $id, $state, \"$content_url\")";
        custom_mysql_query($db, $sql);
        $db->close();
    }
}

function make_links_clickable($text)
{
    if ($text) {
        $text = trim($text);
        while ($text != stripslashes($text)) { $text = stripslashes($text); }    
        $text = strip_tags($text,"<b><i><u>");
        $text = preg_replace("/(?<!http:\/\/)www\./","http://www.",$text);
        $text = preg_replace( "/((http|ftp)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\" target=\"_blank\">\\0</a>",$text);   
    }
    return $text;
}

function get_content_url($url)
{
    if ($url) {
        return image::scrape_og_tag($url);
    }
}

function get_link_from_text($text)
{
    // Check if there is a url in the text
    if(preg_match('/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', $text, $url)) {
        #echo "URL " . $url[0] . PHP_EOL;
        return $url[0];
    } else {
        #echo "No URL" . PHP_EOL;
    }
}

/*
function get_url_from_clickable_link($text)
{
    $matched = preg_match('/href="(.*)"\s/', $tweet, $match);
    echo "Matched $matched" . PHP_EOL;
    echo "Res $match[1]" . PHP_EOL;
    if ($matched) {
        return $match[1];
    }
}
 */

#TODO: put all db funcitonality somewhere common
function custom_mysql_query($db, $query) {
  $doDebug=true; // Set to true when developing and false when you are deploying for real.

  $result=$db->query($query);
  if(!$result) {
    if($doDebug) {
       // We are debugging so show some nice error output
       echo "Query failed\n$query\n";
       #echo $db->error(); // (Is that not the name)
     }
     else {
         #echo "DB query error on $sql";
      // Might want an error message to the user here.
     }
     #exit();
  } else {
      echo "QUERY SUCCESSFUL!!! $query" . PHP_EOL;
  }
}

$tmhOAuth = new tmhOAuth(array(
  'consumer_key'    => 'Mt2NDCK8DUn5BtElaa8zPA',
  'consumer_secret' => 'mcaj6x7dWTwjk9r9xKG2O5BHe36yfLbVwiQDGClpUR8',
  'user_token'      => '618886497-IFdN8g1oaoFFX7611eiwfTvygWUVODVkT2tmSh1Z',
  'user_secret'     => '3cE8LXf9oiXc4446A61IaPwc1UKx1u1ZvxNYDyXYc',
));

$method = 'https://stream.twitter.com/1/statuses/filter.json';
$track = "onlyin,onlyinamerica,onlyinusa,onlyinwashington";
$track = $track . ',onlyinlondon,onlyinla,onlyinchicago,onlyinny,onlyinnyc,onlyinnewyork,onlyintoronto,onlyinatlanta';
$track = $track . ',onlyinsf,onlyinsanfran,onlyinthecity,onlyinsanfrancisco,onlyinboston,onlyinseattle,onlyinredmond,onlyinsydney';
$track = $track . ',onlyincanada,onlyinbrazil,onlyinuk,onlyingermany,onlyinindia,onlyinjapan,onlyinaustralia';
#$track     = tmhUtilities::read_input('Track terms. For multiple terms separate with commas (leave blank for none): ');
#$follow    = tmhUtilities::read_input('Follow accounts. For multiple accounts separate with commas (leave blank for none): ');
#$locations = tmhUtilities::read_input('Bounding boxes (leave blank for none): ');
#$delimited = tmhUtilities::read_input('Delimited? (1,t,true): ');
#$limit     = tmhUtilities::read_input('Stop after how many tweets? (leave blank for unlimited): ');
#$debug     = tmhUtilities::read_input('Debug? (1,t,true): ');
#$raw       = tmhUtilities::read_input('Raw output? (1,t,true): ');
#$raw = "1";

$true = array('1','t','true');

$params = array();
if (strlen($track) > 0)
  $params['track'] = $track;
if (strlen($follow) > 0)
  $params['follow'] = $follow;
if (strlen($locations) > 0)
  $params['locations'] = $locations;
if (in_array($delimited, $true))
  $params['delimited'] = 'length';
if (strlen($limit) > 0)
  $limit = intval($limit);
$debug = in_array($debug, $true);
$raw = in_array($raw, $true);

$tmhOAuth->streaming_request('POST', $method, $params, 'my_streaming_callback');
if ($debug)
  var_dump($tmhOAuth);
?>
