Only.in
=======

Setup Instructions

1. Install the LAMP stack, i.e. Apache 2, MySQL 14.14 (or above) and PHP 5.3.3 (or above)

2. Install git on your Mac and then clone the repo in some directory of your choice (I cloned it in ~/Documents/phpweb/onlyin for example): git clone git@github.com:onlyin/only.in.git

3. Add onlyin.com to your hosts file to point back to localhost:

127.0.0.1 onlyin.com 

onlyin.com will point to your dev instance, whereas only.in will point to the production (linode) instance

4. Add the following entry to the end of your httpd.conf:

### for onlyin.com ###                                                                                                                                                                                        
NameVirtualHost *:80
<VirtualHost *:80>                                                                                                                                                                                       
  ServerName onlyin.com                                                                                                                                                                                  
  ServerAlias www.onlyin.com                                                                                                                                                                     
  DocumentRoot "/Users/rswaminathan/Documents/phpweb/onlyin"
  <Directory "/Users/rswaminathan/Documents/phpweb/onlyin">
    Options +FollowSymlinks
    Order allow,deny
    Allow from all
    AllowOverride All
  </Directory>                                                                                                                                                                                                
</VirtualHost>

Replace your DocumentRoot and <Directory> directive with the correct folder name of your git repo

5. Enable short_open_tags and html_errors in your /etc/php.ini, and ensure magic_quotes_gpc is Off:

short_open_tag = On
html_errors = On
magic_quotes_gpc = Off

6. Install xdebug on your local mac. Will help with debugging and stack traces lot.

7. Import the mysql schema from schema.sql in the git repo into your mysql instance:

# create the onlyin database
mysqladmin -u root create onlyin 

# import the skeleton tables from the text file
mysql -u root onlyin < schema.sql

8. Edit your mysql connection details inside init.php. Do not stage/commit these changes!

9. Browse to onlyin.com !
