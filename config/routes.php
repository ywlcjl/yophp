<?php

/**
 * route customer setting
 * 路由匹配规则,
 * 'e' => 'example',
 * 'e/{id:num}' => 'example/detail',
 * 'e/{id:num}/profile' => 'example/json',
 * 'e/{id:num}/{name:str}' => 'example/detailWithName',
 * 'e/{id:any}' => 'example/sql',
 * 'e/page' => 'example/page',
 * 'json' => 'example/json',
 * 'admin' => 'backend/home/index'
 */

$routes = array(
    'e' => 'example',
    'e/{id:num}' => 'example/detail',
    'e/{id:num}/profile' => 'example/json',
    'e/{id:num}/{name:str}' => 'example/detailWithName',
    'e/{id:any}' => 'example/sql',
    'e/page' => 'example/page',
    'json' => 'example/json',
    'admin' => 'backend/home/index'
);
