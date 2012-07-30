<?php

ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', true);
ini_set('html_errors', true);

chdir(dirname(__FILE__));

if (file_exists('./init.local.php')) {
    require './init.local.php';
}

define('CSRF_SECRET', 'm2*xb23)./2nej3{3$');
define('USER_SALT_LEN', 3);
define('USER_PWD_SECRET', '20)(*2mn3h32119**@#21');
define('SUBIN_MIN_LEN', 2);
define('API_SECRET', '95dbb8238195850');
define('IMGUR_API_KEY', 'a101ec6dba513b6e8a0ad48c7a5a65f0');
define('UPLOAD_MAX_SIZE', 10*1024*1024); // 10M
define('STATIC_PREFIX', '/s');
define('WEB_ROOT', dirname(__FILE__) . '/public');
define('TEMPLATE_ROOT', dirname(__FILE__) . '/views');

session_start();
date_default_timezone_set('America/New_York');

require './functions.php';

// clean up $_GET and $_POST, ensure all values are strings (no arrays)
foreach (array('_GET', '_POST', '_REQUEST', '_COOKIE') as $sglobal) {
    foreach ($$sglobal as $k => $v) {
        $$sglobal[$k] = trim((string) $v);
    }
}
