<?php

class YoView
{
    //protected $variables = array();
    
    private static $_instance;
    
    private $_pageNav;

    protected function __construct() {}
    private function __clone() {}
    public function __wakeup() {}
    
    //单例模式
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /*
     * 渲染视图
     */
    public function render($view, $data=array()) {
        if($data) {
            extract($data);
        }

        $viewFile = VIEW_DIR.$view.'.php';
        
        if(file_exists($viewFile)) {
            include $viewFile;
        }
    }

    /**
     * 输出json
     * @param $response
     * @return void
     */
    public function json($response=[])
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
        exit;
    }
    
    public function getPageNav() {
        return $this->_pageNav;
    }
    
    public function setPageNav($str) {
        $this->_pageNav = $str;
    }

}
