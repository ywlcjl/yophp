<?php

class ExampleModel extends YoModelBase
{
    //模型数据库表名 *必填
    protected static $_name = 'example';

    public $_statuss = array(
        0 => "停用",
        1 => "启用",
        2 => "待定"
    );

    //status名字定义
    public function getStatus($key)
    {
        $value = "";
        if(array_key_exists($key, $this->_statuss)) {
            $value = $this->_statuss[$key];
        }

        return $value;
    }
}

