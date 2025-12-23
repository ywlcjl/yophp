<?php

/**
 * 您可以自定义类和方法, 以单例形式调用方法, spl_autoload_register("loadClass"); 会自己找到这个类并加载
 */
class MyLib
{
    //单例实例化
    private static $_instance;
    
    public function __construct()
    {
    }
    
    //单例模式
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }

    /**
     * 自定义的方法
     * @param $result
     * @return mixed|string
     */
    public function getMyExample($result='success')
    {
        return $result;
    }
    

}
