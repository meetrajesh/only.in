<?php

define('API_SECRET', '95dbb8238195850');
$api_key = sha1(floor(time() / 1800) . API_SECRET);

$username = 'raj';
$username_exists = file_get_contents('http://api.onlyin.com/user/username_exists?api_key=' . $api_key . '&username=' . $username);

var_dump($username_exists);

if (!$username_exists) {
    // create user
    $data['username'] = 'raj';
    $data['password'] = 'raj';
    $data['name'] = 'raj';
    $data['email'] = 'raj@raj.com';
    
    $user_id = file_get_contents('http://api.onlyin.com/user/username_exists?api_key=' . $api_key . '&' . http_build_query($data));
    var_dump($user_id);

}
