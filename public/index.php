<?php
/**
 * 入口文件
 */

//..为上一级目录
define('APP_PATH', __DIR__.'/../');
define('APP_FOLDER', 'app');
define('APP_DIR', APP_PATH.APP_FOLDER.'/');
define('CONFIG_DIR', APP_PATH."config/");
define('FRAMEWORK_DIR', APP_PATH.'framework/');
define('HELPER_DIR', APP_DIR.'helpers/');
define('UPLOADS_DIR', './uploads/');

require FRAMEWORK_DIR.'init.php';
