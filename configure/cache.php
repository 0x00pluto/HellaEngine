<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/21
 * Time: 上午11:47
 */
return [
    'memcached' => [
        \hellaEngine\Configure\Constants::MEMCACHE_HOST => "192.168.1.3",
        \hellaEngine\Configure\Constants::MEMCACHE_PORT => 11211,
        \hellaEngine\Configure\Constants::MEMCACHE_EXPIRATION => 0,
        \hellaEngine\Configure\Constants::MEMCACHE_PREFIX => "cooking_",
        \hellaEngine\Configure\Constants::MEMCACHE_COMPRESSION => false
    ]
];