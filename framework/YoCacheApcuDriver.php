<?php

/*
 * apc缓存处理
 */

class YoCacheApcuDriver extends YoCacheDriver {

    public function __construct() {
        if (!$this->isSupported()) {
            throw new Exception('Cache: Failed to initialize APC. extension not loaded/enabled?');
        }
    }

    /**
     * Get
     *
     * Look for a value in the cache. If it exists, return the data
     * if not, return FALSE
     *
     * @param	string
     * @return	mixed	value that is stored/FALSE on failure
     */
    public function get($id) {
        $success = FALSE;
        $data = apcu_fetch($id, $success);

        return ($success === TRUE) ? $data : FALSE;
    }

    /**
     * Cache Save
     *
     * @param	string	$id	Cache ID
     * @param	mixed	$data	Data to store
     * @param	int	$ttl	Length of time (in seconds) to cache the data
     * @param	bool	$raw	Whether to store the raw value (unused)
     * @return	bool	TRUE on success, FALSE on failure
     */
    public function save($id, $data, $ttl = 60, $raw = FALSE) {
        return apcu_store($id, $data, (int) $ttl);
    }

    /**
     * Delete from Cache
     *
     * @param	mixed	unique identifier of the item in the cache
     * @return	bool	true on success/false on failure
     */
    public function delete($id) {
        return apcu_delete($id);
    }

    /**
     * Clean the cache
     *
     * @return	bool	false on failure/true on success
     */
    public function clean() {
        return apcu_clear_cache('user');
    }

    /**
     * Cache Info
     *
     * @param	string	user/filehits
     * @return	mixed	array on success, false on failure
     */
    public function cacheInfo($type = NULL) {
        return apcu_cache_info($type);
    }

    /**
     * is_supported()
     *
     * Check to see if APC is available on this system, bail if it isn't.
     *
     * @return	bool
     */
    public function isSupported() {
        return extension_loaded('apcu') && ini_get('apc.enabled');
    }

}
