<?php

ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', true);
ini_set('html_errors', true);

session_start();

define('BASE_URL', 'http://onlyin.com');
define('PATH_PREFIX', ''); // eg. /onlyin
define('CSRF_SECRET', 'm2*xb23)./2nej3{3$');
define('USER_SALT_LEN', 3);
define('USER_PWD_SECRET', '20)(*2mn3h32119**@#21');

// database credentials
define('DBHOST', 'localhost');
define('DBUSER', '');
define('DBPASS', '');
define('DBNAME', 'onlyin');

require './functions.php';

date_default_timezone_set('America/New_York');

// clean up $_GET and $_POST, ensure all values are strings (no arrays)
foreach (array('_GET', '_POST', '_REQUEST') as $sglobal) {
    foreach ($$sglobal as $k => $v) {
        $$sglobal[$k] = trim((string) $v);
    }
}


