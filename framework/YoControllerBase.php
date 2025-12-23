<?php

class YoControllerBase {

    protected function __construct() {

    }
    private function __clone() {}
    public function __wakeup() {}

    public function loadHelper($helperName) {
        $file = HELPER_DIR . $helperName . '.php';

        // 使用静态变量做简单的内部缓存，避免重复 include_once 的系统调用开销
        static $loaded = [];
        if (isset($loaded[$helperName])) {
            return;
        }

        if (file_exists($file)) {
            include $file;
            $loaded[$helperName] = true;
        } else {
            if (DEBUG_MODE !== 'production') {
                die("YoPHP Error: Helper file '{$helperName}' not found in " . HELPER_DIR);
            }
        }
    }


    /**
     * 过滤$_GET['key']
     * @param $key
     * @param $default
     * @param $htmlspecialcharsOrAddslashes
     * @return mixed|string|type
     */
    public function get($key, $default='', $htmlspecialcharsOrAddslashes=true) {
        if (isset($_GET[$key])) {
            if($_GET[$key] !== '') {
                if ($htmlspecialcharsOrAddslashes) {
                    return sanitize($_GET[$key]);
                } else {
                    return addslashes(trim($_GET[$key]));
                }
            }
        }
        return $default;
    }

    /**
     * 获取 POST 数据带安全过滤
     */
    public function post($key, $default='', $isHtmlspecialchars=true) {
        if (isset($_POST[$key])) {
            if($_POST[$key] !== '') {
                if ($isHtmlspecialchars) {
                    return sanitize($_POST[$key]);
                } else {
                    return trim($_POST[$key]);
                }
            }
        }
        return $default;
    }

}