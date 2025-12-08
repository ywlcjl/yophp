# Yophp

A simple PHP framework with performance close to native PHP.

## Features

* Support mvc development model
* Close to the execution efficiency of native PHP
* Support MySQL active record model function
* Support for controller module folders
* Provide cache by apcu, redis convenience functions

## Installation

* PHP 7.0 ~ 8.3 or newer, 
* MySQL 5.7 ~ 8.0,
* Create the host with this directory, the host name can use yophp.localhost
* Create database yophp, the charset is utf8 or utf8mb3
* Import the yophp.sql to yophp database
* The virtual host need support rewrited .htaccess file, because the url need to use.
* Visit local the http://yophp.localhost
* Visit other controller url like http://yophp.localhost/example/index
* Visit controller with Moudle url like http://yophp.localhost/backend/home/index
* If you haven't configured a virtual host or not support .htaccess to rewrited, you can use source URL to visit your controller and actions. like this: http://localhost/yophp/index.php?c=home&a=index But it is not a good practice.


## More Content
* This framework is for testing purposes only. If you want to use it in a production environment, please read the source code to ensure the security of your product.
