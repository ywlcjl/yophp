<?php

/**
 * 常用函数库
 */

/**
 * 自动加载函数
 * @param type $className
 */
function loadClass($className)
{
    //UserModel.php 截取Model
    $modelKey = substr($className, -5, 5);

    //Yo_Model.php 截取Yo_
    $frameworklKey = substr($className, 0, 3);

    if ($modelKey == 'Model') {
        $file = APP_DIR . 'models/' . $className . '.php';
        if (file_exists($file)) {
            include_once $file;
        }
    } elseif ($frameworklKey == 'Yo_') {
        //加载框架类
        $file = FRAMEWORK_DIR . $className . '.php';
        if (file_exists($file)) {
            include_once $file;
        }
    } else {
        //加载自定义类
        $file = APP_DIR . 'libraries/' . $className . '.php';
        if (file_exists($file)) {
            include_once $file;
        }
    }
}

/**
 * 基础过滤
 * @param type $str
 * @return type
 */
function clean($str)
{
    $str = trim($str);

    $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    //添加转义
    $str = addslashes($str);
    return $str;
}

/**
 * 获取链接参数
 * @param type $num
 * @return type
 */
function uri($num = '')
{
    $uris = array();
    $uriStr = $_SERVER['REQUEST_URI'];

    //带参数的链接 /home/index/?page=1 去掉?后面的
    $uriStrT = stristr($_SERVER['REQUEST_URI'], '?', true);
    if ($uriStrT !== false) {
        $uriStr = $uriStrT;
    }

    $uriT = explode('/', $uriStr);
    if ($uriT) {
        foreach ($uriT as $key => $value) {
            if ($value) {
                $uris[] = $value;
            }
        }
    }

    if ($num) {
        return isset($uris[$num - 1]) ? $uris[$num - 1] : '';
    } else {
        return $uris;
    }
}

/**
 * 获取视图对象
 * @return Yo_View
 */
function view()
{
    return Yo_View::getInstance();
}

/**
 * 跳转链接
 * @param type $url
 */
function go_url($url)
{
    header("Location:$url");
    exit;
}
