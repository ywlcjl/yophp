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

//路由映射, 设置虚拟主机域名,以及支持.htaccess rewrite的访问路径
if(isset($uris[0]) && $uris[0] != 'index.php') {
    //假设当前 $uris 是 array('e', 'page', '123')
    $matched = false;
    $requestPath = implode('/', $uris); // 组合成完整的请求路径 e/1
    $routeParams = []; // 用于存储提取到的参数，如 ['id' => 1]

    // 静态哈希匹配 (最高优先级 + 极致性能) 只有不含 { 的规则才进入静态匹配，利用 array_key_exists 的 O(1) 性能
    if (isset($routes[$requestPath]) && strpos($requestPath, '{') === false) {
        $routeValue = $routes[$requestPath];
        $uris = array_values(array_filter(explode('/', $routeValue)));
        $matched = true;
    }

    if(!$matched && $routes) {
        foreach ($routes as $rule => $target) {
            if (strpos($rule, '{') === false) continue; // 跳过静态路由
            // 关键正则：匹配 {名称:类型}
            $pattern = '#^' . preg_replace_callback('/\{([a-zA-Z0-9_]+):([a-z]+)\}/', function($matches) {
                    $name = $matches[1]; // 变量名，如 id, name
                    $type = $matches[2]; // 类型，如 num, str, any

                    // 根据类型映射正则
                    switch ($type) {
                        case 'num':
                            $reg = '\d+';
                            break;
                        case 'str':
                            $reg = '[a-zA-Z]+';
                            break;
                        case 'any':
                        default:
                            $reg = '[^/]+';
                            break;
                    }

                    return "(?P<$name>$reg)";
                }, $rule) . '$#';

            if (preg_match($pattern, $requestPath, $matches)) {
                $routeValue = $target;
                foreach ($matches as $key => $value) {
                    if (is_string($key)) $routeParams[$key] = $value;
                }
                $uris = array_values(array_filter(explode('/', $routeValue)));
                $matched = true;
                break;
            }
        }
    }

    //从最长的可能路径开始尝试（比如先试 e/page，再试 e）
    if (!$matched) {
        for ($i = count($uris); $i > 0; $i--) {
            // 截取前 $i 个片段并用 / 连接
            $searchKey = implode('/', array_slice($uris, 0, $i));

            if (array_key_exists($searchKey, $routes)) {
                // 找到了映射值
                $routeValue = $routes[$searchKey];
                // 重新处理 routeValue，去除多余斜杠并转为数组
                $routeUris = array_values(array_filter(explode('/', $routeValue)));
                // 获取路由匹配掉之后剩余的参数（比如 id 等）
                $remainUris = array_slice($uris, $i);
                // 重新合并：映射后的路径 + 剩余的参数
                $uris = array_merge($routeUris, $remainUris);
                $matched = true;
                break; // 匹配到最长的就跳出
            }
        }
    }

    //找到映射后的正式controller和action
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
        $module = sanitizePure($_REQUEST['m']);
    }

    //手动获得controller
    if (isset($_REQUEST['c'])) {
        $controller = sanitizePure($_REQUEST['c']);
    }

    //手动获得action
    if (isset($_REQUEST['a'])) {
        $action = sanitizePure($_REQUEST['a']);
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

$methodParams = $reflection->getParameters();
$callArgs = [];

// 计算传统路径中，参数应该从 $uris 的第几个索引开始
// 如果有 module，参数通常从 $uris[3] 开始；没有 module，则从 $uris[2] 开始
$paramStartIndex = $module ? 3 : 2;

foreach ($methodParams as $key=>$param) {
    $name = $param->getName();
    if (array_key_exists($name, $routeParams)) {
        // 如果方法的参数名（如 $id）在路由匹配结果中存在，则注入
        $callArgs[] = $routeParams[$name];
    } elseif (isset($uris[$paramStartIndex + $key])) {
        // 备选：从传统的 URI 片段中取 (例如 /example/detail/5 中的 5)
        $callArgs[] = $uris[$paramStartIndex + $key];
    } elseif ($param->isDefaultValueAvailable()) {
        // 否则使用方法定义的默认值
        $callArgs[] = $param->getDefaultValue();
    } else {
        // 既没有匹配到，也没有默认值，给 null
        $callArgs[] = null;
    }
}

// 执行方法
$reflection->invokeArgs($classObj, $callArgs);
//执行方法
//$classObj->$action();