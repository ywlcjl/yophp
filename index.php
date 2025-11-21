<?php
/**
 * 入口文件
 */

define('APP_PATH', __DIR__.'/');  //..为上一级目录
define('APP_FOLDER', 'app');
define('APP_DIR', APP_PATH.APP_FOLDER.'/');
define('CONFIG_DIR', APP_PATH."config/");
define('FRAMEWORK_DIR', APP_PATH.'framework/');

require FRAMEWORK_DIR.'init.php';
