<?php

/*
 * 缓存基类
 */

class Yo_Cache {

    //单例实例化
    private static $_instance;
    protected $_cache;
    protected $_cacheDrivers = array(
        'apc',
        'redis',
        'file'
    );
    
    protected $_mainDriver;
    protected $_backupDriver;
//    protected $_adapter;
    //前序符号
    private $key_prefix = '';
    
    public function __construct($mainDriver, $backupDriver) {
        if (!$this->_cache) {

            $this->_mainDriver = $mainDriver;
            $this->_backupDriver = $backupDriver;
            
            $mainDriverClassName = 'Yo_' . ucfirst($mainDriver) . 'Driver';
            $cache = new $mainDriverClassName();
            if (is_object($cache) && $cache->is_supported()) {
//                $this->_adapter = $mainDriver;
                $this->_cache = $cache;
            } else {
                $backupDriverClassName = 'Yo_' . ucfirst($backupDriver) . 'Driver';
                $cache = new $backupDriverClassName();
                if (is_object($cache) && $cache->is_supported()) {
//                    $this->_adapter = $backupDriver;
                    $this->_cache = $cache;
                } else {
                    print('no cache driver supported.');
                }
            }
        }
    }

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
        return $this->_cache->get($this->key_prefix . $id);
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
        return $this->_cache->save($this->key_prefix . $id, $data, $ttl, $raw);
    }

    public function delete($id) {
        return $this->_cache->delete($this->key_prefix . $id);
    }

    public function clean() {
        return $this->_cache->clean();
    }

    public function cache_info($type = 'user') {
        return $this->_cache->cache_info($type);
    }

    
}
