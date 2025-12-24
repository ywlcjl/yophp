# Yophp

A simple PHP framework with high performance close to native PHP.

## Features

* Support mvc development methods
* Close to the execution efficiency of native PHP
* Support MySQL active record model libraries
* Support Controller input form validator
* Provide Cache with apcu, redis libraries
* Provide File upload and picture resize, thumb, captcha libraries
* Provide Complete route config
* Provide Code used examples

## Installation

* PHP 7.0 ~ 8.3 or newer, 
* MySQL 5.7 ~ 8.0, recommend 8.0,
* Create the host decument directory "/public", the host name can use yophp.localhost or other you like it.
* Create database yophp, the charset is utf8mb4
* Import the yophp.sql to yophp database
* The virtual host need support rewrited .htaccess file, because the url need to use.
* If you want use file upload example, and not used windows, you need chmod 777 public/uploads/example
* Visit local the http://yophp.localhost
* Visit controller to action url like http://yophp.localhost/example/index
* Visit moudle controller to action url like http://yophp.localhost/backend/home/index
* If you haven't configured a virtual host or not support .htaccess to rewrited, you can use source URL to visit your controller and actions. like this: http://localhost/yophp/index.php?c=home&a=index But it is not a good practice.


## More Content
* This framework is for testing purposes only. If you want to use it in a production environment, please read the source code to ensure the security of your product.
<img width="1175" height="695" alt="content_1" src="https://github.com/user-attachments/assets/5bf2615f-7c16-478f-99c9-a5c99fe71fa8" />

