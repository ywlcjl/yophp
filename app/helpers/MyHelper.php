<?php
/**
 * Custom Helper Fucntion, 你需要手动加载这个MyHelper文件公共函数
 */

if (!function_exists('myExampleFunction')) {
    /**
     * 自定义Helper函数例子, 仅供参考, 您可以完全自己编写任意自定义函数
     * @param $result
     * @return mixed|string
     */
    function myExampleFunction($result='success')
    {
        return $result;
    }
}

