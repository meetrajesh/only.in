<?php

require dirname(__FILE__) . '/client.php';

define('API_SECRET', '95dbb8238195850');
$api = new OnlyInAPI(API_SECRET);

// =====================================================================================================

// A. Check if username exists
$username = 'raj7';
$json = $api->call('/user/username_exists', array('username' => $username));

// check for api errors, should do this after every api call, and handle error appropriately
if (!empty($json['error'])) {
    die('Only.in API ERROR: ' . htmlspecialchars($json['error']) . "\n");
}

// if no api errors, then inspect the api response payload
var_dump($json);

// =====================================================================================================

// B. create the user if it doesn't exist
$username_exists = $json['username_exists'];
if (!$username_exists) {
    // create user
    $data = array('username' => $username,
                  'password' => 'raj',
                  'name' => 'raj',
                  'email' => 'raj@raj.com');
    
    $json = $api->call('/user/create', $data);

    // check for api errors, should do this after every api call, and handle error appropriately
    if (!empty($json['error'])) {
        die('Only.in API ERROR: ' . htmlspecialchars($json['error']) . "\n");
    }

    // if no api errors, then inspect the api response payload
    var_dump($json);

    $user_id = $json['user_id'];
}

// =====================================================================================================

// C. create a post for that user in subin called 'toronto'
$data = array('subin_name' => 'toronto',
              'username' => $username,
              'content' => ('This is an epic new post ' . time()),
              'num_upvotes' => 4,
              'num_downvotes' => 0);

$json = $api->call('/post/create', $data);
$post_id = $json['post_id'];
var_dump($json);

// =====================================================================================================

// D. create a comment for the above post
$data = array('username' => $username,
              'post_id' => $post_id,
              'comment' => 'this is a test comment',
              'num_upvotes' => 1,
              'num_downvotes' => 4);

$json = $api->call('/comment/create', $data);
$post_id = $json['comment_id'];
var_dump($json);

// =====================================================================================================


