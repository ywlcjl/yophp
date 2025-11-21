<?php

class UserModel extends Yo_ModelBase
{
    //模型表名
    protected static $name = 'user';

    //单例实例化
    private static $_instance;

    public $_sexNames = array(
        0 => "未设置",
        1 => "男",
        2 => "女"
    );
    
    public function __construct()
    {
        parent::__construct();
    }
    
    //单例模式
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        self::$_instance->tableName = self::$name;
        return self::$_instance;
    }

    //sex名字定义
    public function getSexName($key)
    {
        $sexName = "";
        if(array_key_exists($key, $this->_sexNames)) {
            $sexName = $this->_sexNames[$key];
        }

        return $sexName;
    }
}

