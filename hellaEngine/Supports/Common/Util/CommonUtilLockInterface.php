<?php

namespace hellaEngine\Supports\Common\Util;

/**
 * 锁的接口
 *
 * @author zhipeng
 *
 */
interface CommonUtilLockInterface {
	/**
	 * 设置锁的key
	 *
	 * @param unknown $value
	 */
	function set_key($value);
	/**
	 * 获取锁
	 *
	 * @return string
	 */
	function get_key();
	/**
	 * 加锁
	 *
	 * @param integer $timeoutsec
	 *        	超时时间
	 * @param boolean $sync
	 *        	是否同步
	 * @return boolean
	 */
	function lock($timeoutsec, $sync);
	/**
	 * 解锁
	 */
	function unlock();
}