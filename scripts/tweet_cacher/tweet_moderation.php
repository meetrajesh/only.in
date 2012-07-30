<?php

require dirname(__FILE__) . '/../../init.php';
require dirname(__FILE__) . '/../../api/client.php';
require dirname(__FILE__) . '/lib/tmhOAuth/tmhOAuth.php';
require dirname(__FILE__) . '/lib/tmhOAuth/tmhUtilities.php';
require 'tweet_db.php';

if (isset($_POST['tweet_id'])) {

    $id = $_POST['tweet_id'];

    if (isset($_POST['Accept']) && isset($_POST['place']) && isset($_POST['content_url'])) {
        $place = $_POST['place'];
        $title = $_POST['title'];
        $content_url = $_POST['content_url'];
        #echo "Accepted: $place & $content_url & $id";

        $api = new OnlyInAPI(API_SECRET);
        
        $data = array('subin_name' => $place,
                      'username' => 'anonymous',
                      'title' => $title,
                      'content' => $content_url,
                      'caption' => 'test caption',
                      'num_upvotes' => 4,
                      'num_downvotes' => 0);

        if (get_tweet_state($id) === 0) {
            $json = $api->call('/post/create', $data);
            update_tweet_state($id, 1);
            update_content_url($id, $content_url);
            send_tweet("Hello World");
        }
        #$post_id = $json['post_id'];
        #var_dump($json);
    } else if (isset($_POST['Reject'])) {
        update_tweet_state($id, 2);
        #echo "Rejected";
    }
}

function update_tweet_state($tweet_id, $state)
{
    // TODO: remove code duplication
    $db = tweet_db::get_tweet_db();
    if (!$db)
    {
        die('Could not connect: ' . mysql_error());
    }
    
    $sql = 'UPDATE tweets SET state=' . $state . ' WHERE id=' . $tweet_id;
    #echo "SQL: $sql" . PHP_EOL;
    custom_mysql_query($db, $sql);
    $db->close();
}

function get_tweet_state($tweet_id)
{
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

    return $row['state'];
    $db->close();    
}

function update_content_url($tweet_id, $url)
{
    // TODO: remove code duplication
    $db = tweet_db::get_tweet_db();
    if (!$db)
    {
        die('Could not connect: ' . mysql_error());
    }
    $url = $db->real_escape_string($url);
    $sql = 'UPDATE tweets SET content_url="' . $url . '" WHERE id=' . $tweet_id;
    custom_mysql_query($db, $sql);    
    $db->close();
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

function send_tweet($text)
{
    $tmhOAuth = new tmhOAuth(array(
      'consumer_key'    => 'Mt2NDCK8DUn5BtElaa8zPA',
      'consumer_secret' => 'mcaj6x7dWTwjk9r9xKG2O5BHe36yfLbVwiQDGClpUR8',
      'user_token'      => '618886497-IFdN8g1oaoFFX7611eiwfTvygWUVODVkT2tmSh1Z',
      'user_secret'     => '3cE8LXf9oiXc4446A61IaPwc1UKx1u1ZvxNYDyXYc',
    ));

    $code = $tmhOAuth->request('POST', $tmhOAuth->url('1/statuses/update'), array(
      'status' => 'My Twitter Message'
    ));

    if ($code == 200) {
      tmhUtilities::pr(json_decode($tmhOAuth->response['response']));
    } else {
      tmhUtilities::pr($tmhOAuth->response['response']);
    }
}

function display_tweets()
{
    // TODO: remove code duplication
    $db = tweet_db::get_tweet_db();
    if (!$db)
    {
        die('AB Could not connect: ' . mysql_error());
    }
    
    $query = "SELECT * FROM tweets WHERE state=0";
    $result = $db->prepare($query); // prepare your query
    $result->execute(); // now we execute the query
    $result->bind_result($row['stamp'], $row['user'], $row['content'], $row['id'], $row['state'], $row['content_url']); // bind results to $row
    
    # Start the HTML table
    echo '
    <table width="80%" border="1">
    <tr>
      <td width="12%">Username</td>
      <td width="26%">Image</td>
      <td width="26%">Image URL</td>
      <td width="25%">Tweet</td>
      <td width="14%">Place</td>
      <td width="18%">Title</td>
      <td width="0%">Accept</td>
      <td width="0%">Reject</td>
    </tr>';
    
    while($result->fetch()) {
        $tweet = $row['content'];
        $content_url = $row['content_url'];
        
        # Format the HTML
        echo "<tr><td>" . $row['user'] . "</td>";
        
        echo '<FORM NAME ="form1" METHOD ="POST" ACTION = "' . $_SERVER['PHP_SELF'] . '">
        <INPUT TYPE = "HIDDEN" name="tweet_id" value="' . $row["id"] . '"/>';        
        
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
        <td><INPUT TYPE = "Submit" Name = "Accept" VALUE = "Accept"/></td>
        <td><INPUT TYPE = "Submit" Name = "Reject" VALUE = "Reject"/></td>

        </FORM>';
        echo '</tr>';
        
    }
    echo "</table>";    
    $db->close(); 
}

?>
