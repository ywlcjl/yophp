<?php

class Yo_View
{
    //protected $variables = array();
    
    private static $_instance;
    
    private $_page;

    public function __construct() {
        
    }
    
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
    
    public function getPage() {
        return $this->_page;
    }
    
    public function setPage($str) {
        $this->_page = $str;
    }
    
}
