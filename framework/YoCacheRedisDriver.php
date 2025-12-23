<?php

class YoCacheRedisDriver extends YoCacheDriver {
    
    protected $_host = '127.0.0.1';
    protected $_port = 6379;
    protected $_timeout = 0;

    /**
     * Redis connection
     *
     * @var	Redis
     */
    protected $_redis;


    /**
     * Class constructor
     *
     * Setup Redis
     *
     * Loads Redis config file if present. Will halt execution
     * if a Redis connection can't be established.
     *
     * @return	void
     * @see		Redis::connect()
     */
    public function __construct() {
        if (!$this->isSupported()) {
            throw new Exception('Cache: Failed to create Redis object. extension not loaded?');
        }

        $this->_redis = new Redis();

        try {
            $success = $this->_redis->connect($this->_host, $this->_port, $this->_timeout);

            if (!$success) {
                throw new Exception('Redis connection failed.');
            }
            $this->_redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

//            if (isset($config['password']) && !$this->_redis->auth($config['password'])) {
//                print('Cache: Redis authentication failed.');
//            }
        } catch (RedisException $e) {
            print('Cache: Redis connection refused (' . $e->getMessage() . ')');
        }
    }

    /**
     * Get cache
     *
     * @param	string	$key	Cache ID
     * @return	mixed
     */
    public function get($key) {
        $value = $this->_redis->get($key);

        return $value;
    }

    /**
     * Save cache
     *
     * @param	string	$id	Cache ID
     * @param	mixed	$data	Data to save
     * @param	int	$ttl	Time to live in seconds
     * @param	bool	$raw	Whether to store the raw value (unused)
     * @return	bool	TRUE on success, FALSE on failure
     */
    public function save($id, $data, $ttl = 60, $raw = FALSE) {
        return $this->_redis->set($id, $data, $ttl);
    }

    /**
     * Delete from cache
     *
     * @param	string	$key	Cache key
     * @return	bool
     */
    public function delete($key) {
        return (bool)$this->_redis->del($key);
    }

    /**
     * clear cache
     *
     * @return	bool
     * @see		Redis::flushDB()
     */
    public function clear() {
        return $this->_redis->flushDB();
    }


    /**
     * Get cache driver info
     *
     * @param	string	$type	Not supported in Redis.
     * 				Only included in order to offer a
     * 				consistent cache API.
     * @return	array
     * @see		Redis::info()
     */
    public function cacheInfo($type = NULL) {
        return $this->_redis->info();
    }

    /**
     * Check if Redis driver is supported
     *
     * @return	bool
     */
    public function isSupported() {
        return extension_loaded('redis');
    }


}
