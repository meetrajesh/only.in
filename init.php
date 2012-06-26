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
define('SUBIN_MIN_LEN', 3);
define('API_SECRET', '95dbb8238195850');

session_start();
date_default_timezone_set('America/New_York');

require './functions.php';

// clean up $_GET and $_POST, ensure all values are strings (no arrays)
foreach (array('_GET', '_POST', '_REQUEST', '_COOKIE') as $sglobal) {
    foreach ($$sglobal as $k => $v) {
        $$sglobal[$k] = trim((string) $v);
    }
}
