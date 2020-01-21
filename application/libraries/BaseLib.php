<?php

class BaseLib
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
    
    /*
     * 获取id对应的value
     */
    public function getKeyToName($array, $dkey='id', $dvalue='name') {
        $result = array();
        if ($array != NULL && is_array($array)) {
            foreach ($array as $value) {
                $result[$value[$dkey]] = $value[$dvalue];
            }
        }

        return $result;
    }
}
