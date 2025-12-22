<?php

abstract class YoCacheDriver
{
    abstract public function get($id);
    abstract public function save($id, $data, $ttl = 60, $raw = FALSE);
    abstract public function delete($id);
    abstract public function clean();
    abstract public function cacheInfo($type = NULL);
    abstract public function isSupported();
}
