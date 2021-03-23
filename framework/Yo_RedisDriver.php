<?php

class Yo_RedisDriver extends Yo_CacheDriver {
    
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
     * An internal cache for storing keys of serialized values.
     *
     * @var	array
     */
    protected $_serialized = array();

    protected $_sIsMemberName = '_yo_redis_serialized';


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
        if (!$this->is_supported()) {
//            print('Cache: Failed to create Redis object; extension not loaded?');
            return;
        }

        $this->_redis = new Redis();

        try {

            $success = $this->_redis->connect($this->_host, $this->_port, $this->_timeout);

            if (!$success) {
//                print('Cache: Redis connection failed. Check your configuration.');
            }

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

        if ($value !== FALSE && $this->_redis->sIsMember($this->_sIsMemberName, $key)) {
            return unserialize($value);
        }

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
        if (is_array($data) OR is_object($data)) {
            if (!$this->_redis->sIsMember($this->_sIsMemberName, $id) && !$this->_redis->sAdd($this->_sIsMemberName, $id)) {
                return FALSE;
            }

            isset($this->_serialized[$id]) OR $this->_serialized[$id] = TRUE;
            $data = serialize($data);
        } else {
            $this->_redis->sRemove($this->_sIsMemberName, $id);
        }

        return $this->_redis->set($id, $data, $ttl);
    }

    /**
     * Delete from cache
     *
     * @param	string	$key	Cache key
     * @return	bool
     */
    public function delete($key) {
        if ($this->_redis->delete($key) !== 1) {
            return FALSE;
        }

        $this->_redis->sRemove($this->_sIsMemberName, $key);

        return TRUE;
    }

    /**
     * Clean cache
     *
     * @return	bool
     * @see		Redis::flushDB()
     */
    public function clean() {
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
    public function cache_info($type = NULL) {
        return $this->_redis->info();
    }


    /**
     * Check if Redis driver is supported
     *
     * @return	bool
     */
    public function is_supported() {
        return extension_loaded('redis');
    }


    /**
     * Class destructor
     *
     * Closes the connection to Redis if present.
     *
     * @return	void
     */
    public function __destruct() {
        if ($this->_redis) {
            $this->_redis->close();
        }
    }

}
