<?php

class TestModel extends Yo_ModelBase
{
    //模型表名
    protected static $name = 'test';

    //单例实例化
    private static $_instance;
    
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

}

