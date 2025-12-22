<?php

class YoController {

    // 视图对象
    protected $view;
    // 缓存对象
    protected $cache;

    public function __construct() {
        // 初始化视图引擎
        $this->view = YoView::getInstance();

        // 自动关联当前控制器名到模板路径（可选，模仿 CI 的便捷性）
        // $this->view->setControllerName(get_class($this));
    }

    /**
     * 快速加载模型
     * 模仿 CI3: $this->model('user')
     */
    protected function model($name) {
        $className = ucfirst($name) . 'Model';
        if (!isset($this->$className)) {
            // 这里利用我们之前写的 Model 单例
            $this->$className = $className::getInstance();
        }
        return $this->$className;
    }

    /**
     * 快速跳转
     * $this->goUrl('/login')
     */
    protected function goUrl($url) {
        header("Location: " . $url);
        exit;
    }

    /**
     * 获取 POST 数据 (带安全过滤)
     */
    protected function post($key = null, $default = null) {
        if ($key === null) return $_POST;
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
}