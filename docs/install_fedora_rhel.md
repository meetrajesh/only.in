Installing Only.in on Fedora/RHEL
=================================

1. Install the LAMP stack:
    $ sudo su
    $ yum install httpd php mysql mysql-server mysql-devel phpmyadmin
    $ chkconfig httpd on
    $ service httpd restart
    $ systemctl start mysqld.service
    $ systemctl enable mysqld.service

    # set your MySQL root password
    $ mysqladmin -u root password <your password here>

2. Install Git and configure:
    $ sudo su
    $ yum install git
    $ git config --global user.name "Your Name Here"
    $ git config --global user.email "your_email@youremail.com"

3. [Set up your SSH key](https://help.github.com/articles/generating-ssh-keys)

4. Create a new directory for the project and clone your fork of the git repo:
    $ cd /var/www/html
    $ git clone git@github.com:yourusername/only.in.git onlyin
    $ cd onlyin
    $ git remote add upstream git@github.com:onlyin/only.in.git

5. Make sure you are working on the latest stable master:
    $ git pull upstream master
    $ git push origin master

6. Update your `hosts` file to include the correct hostnames:
   $ sudo nano /etc/hosts

   # add the following to the 'hosts' file
   127.0.0.1   only.in.dev
   127.0.0.1   api.only.in.dev

7. Update your httpd.conf file to create virtual hosts:
    $ sudo nano /etc/httpd/conf/httpd.conf

    # add the following to end of the 'httpd.conf' file
    Include /etc/httpd/conf/httpd-vhosts.conf

    $ sudo nano /etc/httpd/conf/httpd-vhosts.conf

    # copy the following into the 'httpd-vhosts.conf' file
    NameVirtualHost *:80

    <VirtualHost *:80>
      ServerName only.in.dev
      DocumentRoot "/var/www/html/onlyin/public"
      <Directory "/var/www/html/onlyin/public">
        Options +FollowSymlinks
        Order allow,deny
        Allow from all
        AllowOverride All
      </Directory>
    </VirtualHost>

    <VirtualHost *:80>
      ServerName api.only.in.dev
      DocumentRoot "/var/www/html/onlyin/api"
      <Directory "/var/www/html/onlyin/api">
        Options +FollowSymlinks
        Order allow,deny
        Allow from all
        AllowOverride All
      </Directory>
    </VirtualHost>

8. Update your `php.ini` file to ensure that the following settings are correctly set:
	$ sudo gedit /etc/php.ini

	# make sure the following are correctly set
	auto_globals_jit = Off
    short_open_tag = On
    html_errors = On
    magic_quotes_gpc = Off
    register_globals = Off
    request_order = "GP"

9. Create the database and MySQL user and import the schema
    $ mysql -u root -p
    mysql> CREATE DATABASE onlyin;
    mysql> GRANT ALL ON onlyin.* TO onlyin@localhost IDENTIFIED BY '<your password here>';
    $ mysql -u root -p onlyin < schema.sql

10. Copy the `init.sample.php` file to `init.local.php` and edit your MySQL connection details:
    $ cp init.sample.php init.local.php

11. Restart Apache:
	$ service httpd restart

12. You are done! Go to http://only.in.dev/
