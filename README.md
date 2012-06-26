Only.in
=======

Setup Instructions

1. Install the LAMP stack, i.e. Apache 2, MySQL 14.14 (or above) and PHP 5.3.3 (or above)

2. Install git on your Mac and then clone the repo in some directory of your choice (I cloned it in ~/Documents/phpweb/onlyin for example): git clone git@github.com:onlyin/only.in.git

3. Create a file called init.local.php in your root checkout directory and add the following details to it:

<?php

define('BASE_URL', 'http://onlyin.com');
define('PATH_PREFIX', ''); // eg. /onlyin

// database credentials
define('DBHOST', 'localhost');
define('DBUSER', '');
define('DBPASS', '');
define('DBNAME', 'onlyin');

Be sure to update your BASE_URL with whatever URL you end up using. Also be sure to update your database connection details.

4. Add onlyin.com to your hosts file to point back to localhost:

127.0.0.1 onlyin.com

onlyin.com will point to your dev instance, whereas only.in will point to the production (linode) instance

5. Add the following entry to the end of your httpd.conf:

### for onlyin.com ###                                                                                                                                                                                        
NameVirtualHost *:80
<VirtualHost *:80>                                                                                                                                                                                       
  ServerName onlyin.com                                                                                                                                                                                  
  ServerAlias www.onlyin.com                                                                                                                                                                     
  DocumentRoot "/Users/rswaminathan/Documents/phpweb/onlyin/public"
  <Directory "/Users/rswaminathan/Documents/phpweb/onlyin/public">
    Options +FollowSymlinks
    Order allow,deny
    Allow from all
    AllowOverride All
  </Directory>                                                                                                                                                                                                
</VirtualHost>

Replace your DocumentRoot and <Directory> directive with the correct folder name of your git repo

6. Enable short_open_tags and html_errors in your /etc/php.ini, ensure magic_quotes_gpc and register_globals is Off, and ensure request_order exists and is set to "GP":

short_open_tag = On
html_errors = On
magic_quotes_gpc = Off
register_globals = Off
request_order = "GP"

7. Install xdebug on your local mac. Will help with debugging and stack traces lot.

8. Import the mysql schema from schema.sql in the git repo into your mysql instance:

# create the onlyin database
mysqladmin -u root create onlyin 

# import the skeleton tables from the text file
mysql -u root onlyin < schema.sql

9. Edit your mysql connection details inside init.php. Do not stage/commit these changes!

10. Browse to onlyin.com !
