<?php

/*
 * 缓存基类
 */

class YoCache {

    //单例实例化
    private static $_instance;
    protected $_cache;
    protected $_cacheDrivers = array(
        'apcu',
        'redis',
        'file'
    );
    
    protected $_mainDriver;
    protected $_backupDriver;
    //前序符号
    private $_keyPrefix = '';

    //当前使用的驱动
    public $_currentDriver;

    public function __construct($mainDriver, $backupDriver) {
        if (!$this->_cache) {

            $this->_mainDriver = $mainDriver;
            $this->_backupDriver = $backupDriver;

            try {
                $this->_cache = $this->_loadDriver($mainDriver);
                $this->_currentDriver = $mainDriver;
            } catch (Exception $e) {
                try {
                    $this->_cache = $this->_loadDriver($backupDriver);
                    $this->_currentDriver = $backupDriver;
                } catch (Exception $e2) {
                    print("YoCache Backup Driver ({$backupDriver}) also failed!");
                }
            }
        }
    }

    private function _loadDriver($driver) {
        $className = 'YoCache' . ucfirst($driver) . 'Driver';

        if (!class_exists($className)) {
            throw new Exception("Driver class {$className} not found.");
        }

        $cache = new $className();

//        if (!$cache->isSupported()) {
//            throw new Exception("Driver {$className} is not supported by environment.");
//        }

        return $cache;
    }

    private function __clone() {}     //防止克隆
    public function __wakeup() {}    //防止反序列化

    public static function getInstance($mainDriver, $backupDriver) {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($mainDriver, $backupDriver);
        }
        return self::$_instance;
    }

    public function getCache() {
        return $this->_cache;
    }

    public function get($id) {
        return $this->_cache->get($this->_keyPrefix . $id);
    }
    
    /**
     * Cache Save
     *
     * @param	string	$id	Cache ID
     * @param	mixed	$data	Data to store
     * @param	int	$ttl	Cache TTL (in seconds)
     * @param	bool	$raw	Whether to store the raw value
     * @return	bool	TRUE on success, FALSE on failure
     */
    public function save($id, $data, $ttl = 60, $raw = FALSE) {
        return $this->_cache->save($this->_keyPrefix . $id, $data, $ttl, $raw);
    }

    public function delete($id) {
        return $this->_cache->delete($this->_keyPrefix . $id);
    }

    public function clear() {
        return $this->_cache->clear();
    }

    public function cacheInfo($type = 'user') {
        return $this->_cache->cache_info($type);
    }

}
