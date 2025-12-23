<?php
/**
 * Config
 */

//Mysql database
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', '8888');
define('DB_NAME', 'yophp');
define('DB_PCONNECT', true);  //MYSQL PCONNECT

//hosting * example http://yophp.localhost
define('APP_DOMAIN', 'http://yophp.localhost');
//custom cookie prefix
define('COOKIE_PREFIX', 'yo_');

//timezone
define('TIMEZONE', 'Asia/Shanghai');

//debug, testing, product, Show the php error message level
define('DEBUG_MODE', 'debug');
