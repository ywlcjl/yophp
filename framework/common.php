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

    //YoModel.php 截取Yo
    $frameworklKey = substr($className, 0, 2);

    if ($modelKey == 'Model') {
        $file = APP_DIR . 'models/' . $className . '.php';
        if (file_exists($file)) {
            include_once $file;
        }
    } elseif ($frameworklKey == 'Yo') {
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
 * 过滤函数
 * @param type $str
 * @return type
 */
function sanitize($str)
{
    $str = trim($str);
    $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');

    return $str;
}

/**
 * 过滤非字母, 非数字, 非_,的字符串
 * @param type $str
 * @return type
 */
function sanitizePure($str)
{
    $str = trim($str);
    $str = preg_replace('/[^a-zA-Z0-9_]/', '', $str);

    return $str;
}

function sanitizeArray($array=array())
{
    if (is_array($array)) {
        return array_map('sanitize', $array);
    }
    return $array;
}

/**
 * 获取链接参数
 * @param type $num
 * @return type
 */
function uri($num = '')
{
    $uris = array();
    $uriStr = trim($_SERVER['REQUEST_URI']);

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
 * @return YoView
 */
function view()
{
    return YoView::getInstance();
}

/**
 * 跳转链接
 * @param type $url
 */
function goUrl($url)
{
    header("Location:$url");
    exit;
}


/**
 * 获取缩略图文件名
 * @param type $path
 * @param type $size
 * @return stringWithSizeName
 */
function getImgPath($path, $size = 'thumb')
{
    $newPath = $path;
    if ($size) {
        $newPath = substr($path, 0, -(strlen($path) - strrpos($path, '.'))) . '_' . $size . substr($path, strrpos($path, '.'));
    }
    return $newPath;
}

/**
 * 返回文件类型名
 * @param $src
 * @return false|string
 */
function getFileType($src)
{
    return substr($src, strrpos($src, '.') + 1, strlen($src));
}

/**
 * 放入数组, 重组获得获取id对应的value
 * @param $array
 * @param $dataKey
 * @param $dataValue
 * @return array
 */
function getKeyToName($array, $dataKey='id', $dataValue='name') {
    $result = array();
    if ($array != NULL && is_array($array)) {
        foreach ($array as $value) {
            $result[$value[$dataKey]] = $value[$dataValue];
        }
    }

    return $result;
}