<?php

class YoCacheFileDriver extends YoCacheDriver {

    /**
     * Directory in which to save cache files
     *
     * @var string
     */
    protected $_cachePath = APP_PATH.'cache/';

    /**
     * Initialize file-based cache
     *
     * @return	void
     */
    public function __construct() {
        if (!$this->isSupported()) {
            throw new Exception('Cache: Failed to initialize File.');
        }
    }


    /**
     * Fetch from cache
     *
     * @param	string	$id	Cache ID
     * @return	mixed	Data on success, FALSE on failure
     */
    public function get($id) {
        $data = $this->_get($id);
        return is_array($data) ? $data['data'] : FALSE;
    }


    /**
     * Save into cache
     *
     * @param	string	$id	Cache ID
     * @param	mixed	$data	Data to store
     * @param	int	$ttl	Time to live in seconds
     * @param	bool	$raw	Whether to store the raw value (unused)
     * @return	bool	TRUE on success, FALSE on failure
     */
    public function save($id, $data, $ttl = 60, $raw = FALSE) {
        $path = $this->_getFilePath($id);
        $contents = serialize([
            'time' => time(),
            'ttl' => $ttl,
            'data' => $data
        ]);

        if (file_put_contents($path, $contents, LOCK_EX)) {
            chmod($path, 0640);
            return TRUE;
        }

        return FALSE;
    }


    /**
     * Delete from Cache
     *
     * @param	mixed	unique identifier of item in cache
     * @return	bool	true on success/false on failure
     */
    public function delete($id) {
        $path = $this->_getFilePath($id);
        return is_file($path) ? unlink($path) : FALSE;
    }


    /**
     * Clean the Cache
     *
     * @return	bool	false on failure/true on success
     */
    public function clean() {
        return delete_files($this->_cachePath, FALSE, TRUE);
    }

    /**
     * Cache Info
     *
     * Not supported by file-based caching
     *
     * @param	string	user/filehits
     * @return	mixed	FALSE
     */
    public function cacheInfo($type = NULL) {
        return get_dir_file_info($this->_cachePath);
    }

    /**
     * Is supported
     *
     * In the file driver, check to see that the cache directory is indeed writable
     *
     * @return	bool
     */
    public function isSupported() {
        return is_writable($this->_cachePath);
    }

    /**
     * Get all data
     *
     * Internal method to get all the relevant data about a cache item
     *
     * @param	string	$id	Cache ID
     * @return	mixed	Data array on success or FALSE on failure
     */
    protected function _get($id) {
        $path = $this->_getFilePath($id);
        if (!is_file($path)) {
            return FALSE;
        }

        $data = unserialize(file_get_contents($path));

        if ($data['ttl'] > 0 && time() > $data['time'] + $data['ttl']) {
            file_exists($path) && unlink($path);
            return FALSE;
        }

        return $data;
    }
    protected function _getFilePath($id) {
        // 使用 MD5 确保文件名安全且唯一
        return $this->_cachePath . 'cache_' .md5($id);
    }

}
