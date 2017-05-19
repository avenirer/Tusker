# Tusker
Tusker is a simple task manager created in CodeIgniter 3.x

First of all you need a fresh CodeIgniter. Once you have it, copy/paste the application directory, .htaccess and the assets directory. 

In order to install Tusker you need a database. 

To use a mysql database, you need to import tusker.sql into your database, and change the database credentials in ./application/config/database.php accordingly. 

To use an included sqlite3 database, just change the database group from "default" to "sqlite3" in ./application/config/database.php.

Also take a look at the config.php file inside application/config directory and make any changes you need.

In order to login, you need to use:

**Username:** administrator

**Password:** password
