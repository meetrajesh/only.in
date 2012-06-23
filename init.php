<?php

ini_set('display_errors', true);

define('BASE_URL', 'http://localhost');
define('PATH_PREFIX', '/onlyin');

// database credentials
define('DBHOST', 'localhost');
define('DBUSER', '');
define('DBPASS', '');
define('DBNAME', 'onlyin');

require './functions.php';
require './lib/db.php';

date_default_timezone_set('America/New_York');