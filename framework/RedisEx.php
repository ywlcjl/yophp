<?php

class RedisEx {
    //单例实例化
    private static $_instance;
    
    private $_redis;
    
    public function __construct() {
        if (!$this->_redis) {
            try {
                $redis = new Redis();
                $redis->connect('127.0.0.1', 6379);
                
                $this->_redis = $redis;
            } catch (PDOException $e) {
                if (DEBUG_MODE != 'production') {
                    print "Error!: " . $e->getMessage() . "<br/>";
                }
            }
        }
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
     * 获取一个redis对象 
     */
    public function getRedis() {
        return $this->_redis;
    }

    /*
     * 获取已存储的值
     */
    public function get($key, $unserialize=false) {
        if($unserialize) {
            $str = $this->_redis->get($key);
            if($str) {
                $data = unserialize($str);
            }
        } else {
            $data = $this->_redis->get($key);
        }
        
        return $data;
    }

    /*
     * 设置一个值, 如果是数组则序列化处理
     */
    public function set($key, $value, $expireTime='') {
        $setValue = is_array($value) ? serialize($value) : $value;
        if($expireTime > 0) {
            $this->_redis->setex($key, $expireTime, $setValue);
        } else {
            $this->_redis->set($key, $setValue);
        }
        return true;
    }
    
    public function delete($key) {
        $this->_redis->delete($key);
    }
}