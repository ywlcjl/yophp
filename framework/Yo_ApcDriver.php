<?php

/*
 * apc缓存处理
 */

class Yo_ApcDriver extends Yo_CacheDriver {

    public function __construct() {
        if (!$this->is_supported()) {
            //print('Cache: Failed to initialize APC; extension not loaded/enabled?');
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
        $data = apc_fetch($id, $success);

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
        return apc_store($id, $data, (int) $ttl);
    }

    /**
     * Delete from Cache
     *
     * @param	mixed	unique identifier of the item in the cache
     * @return	bool	true on success/false on failure
     */
    public function delete($id) {
        return apc_delete($id);
    }

    /**
     * Clean the cache
     *
     * @return	bool	false on failure/true on success
     */
    public function clean() {
        return apc_clear_cache('user');
    }

    /**
     * Cache Info
     *
     * @param	string	user/filehits
     * @return	mixed	array on success, false on failure
     */
    public function cache_info($type = NULL) {
        return apc_cache_info($type);
    }

    /**
     * is_supported()
     *
     * Check to see if APC is available on this system, bail if it isn't.
     *
     * @return	bool
     */
    public function is_supported() {
        return (extension_loaded('apc') && ini_get('apc.enabled'));
    }

}
