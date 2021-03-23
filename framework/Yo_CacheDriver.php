<?php

abstract class Yo_CacheDriver
{
    abstract public function get($id);
    abstract public function save($id, $data, $ttl = 60, $raw = FALSE);
    abstract public function delete($id);
    abstract public function clean();
    abstract public function cache_info($type = NULL);
    abstract public function is_supported();
}
