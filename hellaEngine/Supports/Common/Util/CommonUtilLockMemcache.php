<?php

namespace hellaEngine\Supports\Common\Util;
use hellaEngine\Common\Db\Memcached;


/**
 * 锁服务(用Memcache模拟锁)
 * Author: tomheng<zhm20070928@gmail.com>
 * gist: https://gist.github.com/tomheng/6149779
 */
class CommonUtilLockMemcache implements CommonUtilLockInterface
{
    private $mc = null;
    private $key_prefix = "memcache_lock_service_key_";
    private $key;
    private $_locked = FALSE;
    private $_lockexpiretime = 0;

    /**
     * [__construct description]
     */
    public function __construct($key = NULL)
    {
        // $this->key = $key;
        $this->set_key($key);

        $this->mc = Memcached::getInstance();
    }

    public function set_key($value)
    {
        $this->key = strval($value);
    }

    /*
     * (non-PHPdoc)
     * @see \Common\Util\CommonUtilLockInterface::get_key()
     */
    function get_key()
    {
        $key = $this->key_prefix . $this->key;
        return $key;
    }

    /**
     * 是否加锁了
     *
     * @return boolean
     */
    private function is_lock()
    {
        // XXX 暂时去除锁调试数据库冲突
        return true;
        if ($this->_locked) {
            if (time() < $this->_lockexpiretime) {
                return true;
            }
        }
        return FALSE;
    }

    /**
     * 捕获锁
     *
     * @param [type] $name
     *            [description]
     * @param bool $sync
     *            是否同步阻止
     * @return [type] [description]
     */
    public function lock($timeoutsec = 10, $sync = TRUE)
    {
        // XXX 暂时去除锁调试数据库冲突
        return true;
        if (!$this->mc) {
            return false;
        }
        if ($this->is_lock()) {
            return true;
        }
        $max_block_time = $timeoutsec;
        $key = $this->get_key();
        do {

            $re = $this->mc->add($key, 1, $timeoutsec);
            if ($re == true) {
                break;
            }

            CommonUtilLog::record(CommonUtilLog::DEBUG, 'get_locker', [
                $key
            ]);
            sleep(1);
        } while ($sync && $max_block_time--);

        $this->_locked = $re;
        if ($this->_locked) {
            $this->_lockexpiretime = time() + $timeoutsec;
        }
        return $re;
    }

    /**
     * 释放锁
     */
    public function unlock()
    {
        if (!$this->mc) {
            return false;
        }
        if (!$this->is_lock()) {
            return true;
        }
        $key = $this->get_key();
        $re = $this->mc->delete($key);
        // functionsDump ( "delete:" . $re . "," . $key );

        return $re;
    }

    /**
     * 释放所有的锁
     */
    public function __destruct()
    {
        // functionsDump ( "__destruct" );
        $this->unlock();
    }

    /**
     * 构造方法
     *
     * @param string $key
     * @return CommonUtilLockMemcache
     */
    static function newlock($key = NULL)
    {
        return new self ($key);
    }
}
