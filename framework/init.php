<?php
/**
 * init framework
 */

require CONFIG_DIR.'config.php';
require CONFIG_DIR.'routes.php';
require FRAMEWORK_DIR.'common.php';

define('CONTROLLER_DIR', APP_DIR.'controllers/');
define('VIEW_DIR', APP_DIR.'views/');

date_default_timezone_set(TIMEZONE);

//设置错误报告等级
if (defined('DEBUG_MODE')) {
    switch (DEBUG_MODE) {
        case 'debug':
            //显示错误提示
            ini_set('display_errors',1);
            error_reporting(E_ALL & ~E_NOTICE);
            break;
        case 'testing':
            ini_set('display_errors',1);
            error_reporting(E_ALL);
            break;
        case 'product':
            error_reporting(0);
            break;
        default:
    }
}

//注册自动加载类, loadClass 该函数明细在commom.php
spl_autoload_register("loadClass");

//默认控制器
$controller = 'home';
//默认方法
$action = 'index';

//模块目录,默认为空
$module = '';

//获取链接uri数组
$uris = uri();

//路由映射
if(isset($uris[0]) && $uris[0] != 'index.php') {
    //设置虚拟主机域名,以及支持.htaccess rewrite的访问路径
    if (count($uris) > 0 && array_key_exists($uris[0], $routes)) {
        //路由设定映射值
        $routeValue = $routes[$uris[0]];
        $routeUriT = explode('/', $routeValue);
        $routeUris = array();

        if ($routeUriT) {
            foreach ($routeUriT as $key => $value) {
                if ($value) {
                    $routeUris[] = $value;
                }
            }
        }
        //路由映射存在则uris重新赋值
        $uris = $routeUris;
    }

    $uriCount = 0;
    if ($uris) {
        foreach ($uris as $key => $value) {
            if (!is_numeric($value)) {
                $uriCount++;
            }
        }
    }

    if ($uriCount > 2) {
        //带模块的路径
        $module = $uris[0];
        $controller = $uris[1];
        $action = $uris[2];
    } elseif ($uriCount > 1) {
        //模块+控制器 或
        if (is_dir(CONTROLLER_DIR . $uris[0])) {
            $module = $uris[0];
            $controller = $uris[1];
        } else {
            //带action方法的路径
            $controller = $uris[0];
            $action = $uris[1];
        }
    } elseif ($uriCount > 0) {
        //带controller的路径
        $controller = $uris[0];
    }
}else {
    //没有路径参数,则查找输入参数 http://yophp.localhost/index.php?m=&c=&a=
    //手动获得module
    if (isset($_REQUEST['m'])) {
        $module = cleanRoute($_REQUEST['m']);
    }

    //手动获得controller
    if (isset($_REQUEST['c'])) {
        $controller = cleanRoute($_REQUEST['c']);
    }

    //手动获得action
    if (isset($_REQUEST['a'])) {
        $action = cleanRoute($_REQUEST['a']);
    }
}

$controllerName = ucfirst($controller).'Controller';

//加载控制器
$moduleDir = $module ? "$module/" : '';
$controllerFile = CONTROLLER_DIR.$moduleDir.$controllerName.'.php';

$realControllerPath = realpath($controllerFile); //如果文件不存在,会返回false
$basePath = realpath(CONTROLLER_DIR);

// 校验：文件存在且必须在 CONTROLLER_DIR 目录内,
if($realControllerPath && strpos($realControllerPath, $basePath) === 0){
    include $realControllerPath;
} else {
    header('HTTP/1.1 404 Not Found');
    die('Controller not found');
}

if (!class_exists($controllerName)) {
    header('HTTP/1.1 404 Not Found');
    die('class not exists '.$controllerName);
}

$classObj = new $controllerName();

//检查方法名是否合法（禁止魔术方法和以 _ 开头的私有逻辑）
if (strpos($action, '_') === 0 || in_array(strtolower($action), ['__construct', '__destruct', 'get_instance'])) {
    header('HTTP/1.1 404 Not Found');
    die("Forbidden method name");
}

if (!method_exists($classObj, $action)) {
    header('HTTP/1.1 404 Not Found');
    die("method not exists $controllerName $action");
}

//极致性能：确保它是 public 方法（防止调用 protected/private）
$reflection = new ReflectionMethod($classObj, $action);
if (!$reflection->isPublic()) {
    header('HTTP/1.1 404 Not Found');
    die("method is not public");
}

//执行方法
$classObj->$action();
