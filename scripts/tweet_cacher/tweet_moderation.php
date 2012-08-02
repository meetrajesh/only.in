<?php

require dirname(__FILE__) . '/../../init.php';
require dirname(__FILE__) . '/../../api/client.php';
require dirname(__FILE__) . '/lib/tmhOAuth/tmhOAuth.php';
require dirname(__FILE__) . '/lib/tmhOAuth/tmhUtilities.php';

require 'tweet_db.php';

if (isset($_POST['tweet_id'])) {

    $id = $_POST['tweet_id'];
    #var_dump($_POST);
    if ((isset($_POST['Accept']) || isset($_POST['Accept_&_Tweet'])) && isset($_POST['place']) && isset($_POST['content_url'])) {
        $place = $_POST['place'];
        $title = $_POST['title'];
        $content_url = $_POST['content_url'];
        $caption = $_POST['caption'];
        #TODO: Figure out how this got inserted
        $caption = preg_replace('/\\\\"/','', $caption);
                $user = $_POST['user'];

        $api = new OnlyInAPI(API_SECRET);
        $data = array('subin_name' => $place,
                      'username' => 'anonymous',
                      'title' => $title,
                      'content' => $content_url,
                      'caption' => $caption,
                      'num_upvotes' => rand(2,7),
                      'num_downvotes' => 0);

        if (get_tweet_state($id) === 0) {
            $json = $api->call('/post/create', $data);
            update_tweet_state($id, 1);
            update_content_url($id, $content_url);
            if (isset($_POST['Accept_&_Tweet'])) {
                $place_no_spaces = str_replace(' ', '', $place);
                $place_dashes = str_replace(' ', '-', $place);
                if ($title) {
                    $title_grabber = "$title (and more)";
                }
                $tweet = "@$user ";
                $tweet = $tweet . "$title_grabber #onlyin$place_no_spaces : http://only.in/$place_dashes";
                send_tweet($tweet, $id);
            }
        }
        #$post_id = $json['post_id'];
        #var_dump($json);
    } else if (isset($_POST['Reject'])) {
        update_tweet_state($id, 2);
    } else {
        echo "Invalid Response" . PHP_EOL;
    }
}

function update_tweet_state($tweet_id, $state) {
    // TODO: remove code duplication
    $db = tweet_db::get_tweet_db();
    if (!$db)
    {
        die('Could not connect: ' . mysql_error());
    }
    
    $sql = 'UPDATE tweets SET state=' . $state . ' WHERE id=' . $tweet_id;
    custom_mysql_query($db, $sql);
}

function get_tweet_state($tweet_id) {
    // TODO: remove code duplication
    $db = tweet_db::get_tweet_db();
    if (!$db)
    {
        die('Could not connect: ' . mysql_error());
    }
    $query = "SELECT state FROM tweets WHERE id=$tweet_id";
    $result = $db->prepare($query); // prepare your query
    $result->execute(); // now we execute the query
    $result->bind_result($row['state']); // bind results to $row
    $result->fetch(); 
    $state = $row['state'];
    return $state;
}

function update_content_url($tweet_id, $url) {
    // TODO: remove code duplication
    $db = tweet_db::get_tweet_db();
    if (!$db)
    {
        die('Could not connect: ' . mysql_error());
    }
    $url = $db->real_escape_string($url);
    $sql = 'UPDATE tweets SET content_url="' . $url . '" WHERE id=' . $tweet_id;
    custom_mysql_query($db, $sql);    
}

# TODO: Move db functions somewhere common
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
      #echo PHP_EOL . "QUERY SUCCESSFUL!!! " . $query . PHP_EOL;
      
  }
}

function send_tweet($text, $reply_to_id) {
    #0nlyin Twitter Account Info
    $tmhOAuth = new tmhOAuth(array(
      'consumer_key'    => TWEET_CONSUMER_KEY,
      'consumer_secret' => TWEET_CONSUMER_SECRET,
      'user_token'      => TWEET_USER_TOKEN,
      'user_secret'     => TWEET_USER_SECRET,
    ));

    $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
        'status' => $text,
        'in_reply_to_status_id' => $reply_to_id
    ));

    if ($code == 200) {
        #All Good
        #tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
    } else {
        tmhUtilities::pr($tmhOAuth->response['response']);
    }
}

function remove_link($text) {
    $text = preg_replace('/<[^>]*>/', '', $text);
    $text = preg_replace('((?:http|https)(?::\\/{2}[\\w]+)(?:[\\/|\\.]?)(?:[^\\s"]*))', '', $text);
    return $text;
}

function display_tweets() {
    // TODO: remove code duplication
    $db = tweet_db::get_tweet_db();
    if (!$db)
    {
        die('AB Could not connect: ' . mysql_error());
    }
    
    $query = "SELECT * FROM tweets WHERE state=0";
    $result = $db->prepare($query); // prepare your query
    if (!$result) {
        die("Could not display tweets. Try again." . PHP_EOL);
    }

    $result->execute(); // now we execute the query
    $result->bind_result($row['stamp'], $row['user'], $row['content'], $row['id'], $row['state'], $row['content_url']); // bind results to $row
    
    # Start the HTML table
    echo '
    <table width="80%" border="1">
    <tr>
      <td width="12%">Username</td>
      <td width="0%">Image</td>
      <td width="26%">Image URL</td>
      <td width="25%">Tweet</td>
      <td width="14%">Place</td>
      <td width="18%">Title</td>
      <td width="20%">Caption</td>
      <td width="0%">Accept</td>
      <td width="0%">Accept & Tweet</td>
      <td width="0%">Reject</td>
    </tr>';

    while($result->fetch()) {
        $tweet = $row['content'];
        $content_url = $row['content_url'];
        $user = $row['user'];
        $tweet_without_link = remove_link($tweet) . ' <a href="https://twitter.com/' . strtolower($user) . '/status/' . $row['id'] . '" target="_blank">(tweet)</a>';
        $tweet_without_link = htmlspecialchars($tweet_without_link);

        # Format the HTML
        echo "<tr><td>" . $row['user'] . "</td>";
        
        echo '<FORM NAME ="form1" METHOD ="POST" ACTION = "' . $_SERVER['PHP_SELF'] . '">
        <INPUT TYPE = "HIDDEN" name="tweet_id" value="' . $row["id"] . '"/>        
        <INPUT TYPE = "HIDDEN" name="user" value="' . $user . '"/>';        
        
        if ($content_url) {
            echo "<td><img src=\"$content_url\"/></td>";
            $content_url = '"'.$content_url.'"';
        } else {
            echo "<td>Image Place Holder</td>";
        }
        echo "<td><INPUT TYPE = \"TEXT\"   name=\"content_url\" VALUE =$content_url></td>";
        echo "<td>$tweet</td>";
        
        echo '
        <td><INPUT TYPE = "TEXT"   name="place" VALUE =""/></td>
        <td><INPUT TYPE = "TEXT"   name="title" VALUE =""/></td>
        <td><INPUT TYPE = "TEXT"   name="caption" VALUE ="'.$tweet_without_link.'"/></td>
        <td><INPUT TYPE = "Submit" Name = "Accept" VALUE = "Accept"/></td>
        <td><INPUT TYPE = "Submit" Name = "Accept & Tweet" VALUE = "Accept & Tweet"/></td>
        <td><INPUT TYPE = "Submit" Name = "Reject" VALUE = "Reject"/></td>

        </FORM>';
        echo '</tr>';
        
    }
    echo "</table>";    
}

?>
