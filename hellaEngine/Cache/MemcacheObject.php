<?php

namespace hellaEngine\Cache;

/**
 * memcache缓存对象
 *
 * @author zhipeng
 *
 */
class MemcacheObject
{
    /**
     *
     * @var string
     *
     */
    private $key = '';
    /**
     *
     * @var Memcached
     */
    private $memcache_ins = null;

    function __construct($key)
    {
        $this->key = $key;
        $this->memcache_ins = Memcached::getInstance();
    }

    /**
     * 设置值
     *
     * @param mixed $value
     * @param int $expiration
     */
    function set_value($value, $expiration = 0)
    {
        $this->memcache_ins->set($this->key, $value, $expiration);
    }

    /**
     * 获取值
     * @param null $defaultValue
     * @return array|null
     */
    function get_value($defaultValue = NULL)
    {
        $returnValue = $this->memcache_ins->get($this->key);
        if ($returnValue === FALSE) {
            $returnValue = $defaultValue;
        }
        return $returnValue;
    }

    /**
     * 是否有值
     *
     * @return boolean
     */
    function has_value()
    {
        $returnValue = $this->memcache_ins->get($this->key);
        if ($returnValue === FALSE) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 删除值
     *
     * @return true
     */
    function del_value()
    {
        return $this->memcache_ins->delete($this->key);
    }

    /**
     * 创建对象
     *
     * @param string $key
     * @param string $defaultValue
     * @param int $expiration
     * @return MemcacheObject
     */
    static function create($key, $defaultValue = NULL, $expiration = 0)
    {
        $ins = new self ($key);
        if (!is_null($defaultValue)) {
            $ins->set_value($defaultValue, $expiration);
        }
        return $ins;
    }

    /**
     * 删除key
     *
     * @param string $key
     * @return bool
     */
    static function delete($key)
    {
        $ins = self::create($key);
        return $ins->del_value();
    }
}