<?php

class YoControllerBase {

    protected function __construct() {

    }
    private function __clone() {}
    public function __wakeup() {}

    protected function loadHelper($helperName) {
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
    protected function get($key, $default='', $isHtmlspecialchars=true) {
        if (isset($_GET[$key])) {
            if($_GET[$key] !== '') {
                if ($isHtmlspecialchars) {
                    return sanitize($_GET[$key]);
                } else {
                    return trim($_GET[$key]);
                }
            }
        }
        return $default;
    }

    /**
     * 获取 POST 数据带安全过滤
     */
    protected function post($key, $default='', $isHtmlspecialchars=true) {
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

    /**
     * 设置 Cookie, 默认系统会添加cookie的前序, COOKIE_PREFIX.$name
     * @param string $name   名称
     * @param string $value  值
     * @param int    $expire 过期时间（秒，如 3600 代表 1 小时后过期）
     * @param string $path   路径
     * @param bool   $httpOnly 禁止 JS 读取（默认开启，增强安全）
     */
    protected function setCookie($name, $value = '', $expire = 0, $path = '/', $httpOnly = true) {
        // 获取配置中的前缀（如果有的话）
        $name = COOKIE_PREFIX . $name;

        $expiry = ($expire > 0) ? time() + $expire : 0;

        // PHP 7.3+ 推荐使用数组参数，支持 SameSite
        if (PHP_VERSION_ID >= 70300) {
            setcookie($name, $value, [
                'expires'  => $expiry,
                'path'     => $path,
                'domain'   => '', // 默认为当前域名
                'secure'   => isset($_SERVER['HTTPS']), // 如果是 HTTPS 则开启加密传输
                'httponly' => $httpOnly,
                'samesite' => 'Lax' // 现代浏览器必备，防御 CSRF
            ]);
        } else {
            // 兼容老版本 PHP 7.0
            setcookie($name, $value, $expiry, $path, '', isset($_SERVER['HTTPS']), $httpOnly);
        }
    }

    /**
     * 获取 Cookie
     */
    protected function getCookie($name, $default = '', $isHtmlspecialchars=true) {
        $prefixedName = COOKIE_PREFIX . $name;

        if (isset($_COOKIE[$prefixedName])) {
            if ($isHtmlspecialchars) {
                return sanitize($_COOKIE[$prefixedName]);
            } else {
                return trim($_COOKIE[$prefixedName]);
            }
        }

        return $default;
    }

    /**
     * 删除 Cookie
     */
    protected function deleteCookie($name, $path = '/') {
        $this->setCookie($name, '', -3600, $path);
    }

    protected function clearAllCookies($path = '/') {
        $prefix = (string)COOKIE_PREFIX;
        foreach ($_COOKIE as $key => $value) {
            if ($prefix === '' || strpos($key, $prefix) === 0) {
                setcookie($key, '', time() - 3600, $path);

                unset($_COOKIE[$key]);
            }
        }
    }

}