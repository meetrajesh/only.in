<?php

ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', true);
ini_set('html_errors', true);

chdir(dirname(__FILE__));

if (file_exists('./init.local.php')) {
    require './init.local.php';
}

session_start();
date_default_timezone_set('America/New_York');

require './functions.php';

add_define('CSRF_SECRET', 'm2*xb23)./2nej3{3$');
add_define('USER_SALT_LEN', 3);
add_define('USER_PWD_SECRET', '20)(*2mn3h32119**@#21');
add_define('SUBIN_MIN_LEN', 2);
add_define('API_SECRET', '95dbb8238195850');
add_define('IMGUR_API_KEY', 'a101ec6dba513b6e8a0ad48c7a5a65f0');
add_define('UPLOAD_MAX_SIZE', 10*1024*1024); // 10M
add_define('STATIC_PREFIX', '/s');
add_define('WEB_ROOT', dirname(__FILE__) . '/public');
add_define('TEMPLATE_ROOT', dirname(__FILE__) . '/views');
add_define('DEFAULT_NUM_POSTS', 25);
add_define('MAX_VOTES_PER_IP', 3);

// clean up $_GET and $_POST, ensure all values are strings (no arrays)
foreach (array('_GET', '_POST', '_REQUEST', '_COOKIE') as $sglobal) {
    foreach ($$sglobal as $k => $v) {
        $$sglobal[$k] = trim((string) $v);
    }
}
