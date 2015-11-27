<?php

namespace hellaEngine\Supports\utils\schedule;

use hellaEngine\data\BaseDataCell;

/**
 * 计划任务
 *
 * @author zhipeng
 *
 */
class Task extends BaseDataCell {

	/**
	 * 任务类型
	 *
	 * @var string
	 */
	const DBKey_tasktype = "tasktype";
	/**
	 * 获取 任务类型
	 */
	public function get_tasktype() {
		return $this->getdata ( self::DBKey_tasktype );
	}
	/**
	 * 设置 任务类型
	 *
	 * @param integer $value
	 * @see Constants
	 */
	public function set_tasktype($value) {
		$value = intval ( $value );
		$this->setdata ( self::DBKey_tasktype, $value );
	}
	/**
	 * 设置 任务类型 默认值
	 */
	protected function _set_defaultvalue_tasktype() {
		$this->set_defaultkeyandvalue ( self::DBKey_tasktype, Constants::RUN_AT_TIME );
	}

	/**
	 * 任务配置运行的时间 一天0-24小时.转化为秒数
	 *
	 * @var string
	 */
	const DBKey_taskrunattime = "taskrunattime";
	/**
	 * 获取 任务配置运行的时间 一天0-24小时.转化为秒数
	 */
	public function get_taskrunattime() {
		return $this->getdata ( self::DBKey_taskrunattime );
	}
	/**
	 * 设置 任务配置运行的时间 一天0-24小时.转化为秒数
	 *
	 * @param unknown $value
	 */
	public function set_taskrunattime($value) {
		$value = strval ( $value );
		$this->setdata ( self::DBKey_taskrunattime, $value );
	}
	/**
	 * 设置 任务配置运行的时间 一天0-24小时.转化为秒数 默认值
	 */
	protected function _set_defaultvalue_taskrunattime() {
		$this->set_defaultkeyandvalue ( self::DBKey_taskrunattime, 0 );
	}

	/**
	 * 任务配置运行间隔 秒
	 *
	 * @var string
	 */
	const DBKey_taskruntimeinterval = "taskruntimeinterval";
	/**
	 * 获取 任务配置运行间隔 秒
	 */
	public function get_taskruntimeinterval() {
		return $this->getdata ( self::DBKey_taskruntimeinterval );
	}
	/**
	 * 设置 任务配置运行间隔 秒
	 *
	 * @param unknown $value
	 */
	public function set_taskruntimeinterval($value) {
		$value = intval ( $value );
		$this->setdata ( self::DBKey_taskruntimeinterval, $value );
	}
	/**
	 * 设置 任务配置运行间隔 秒 默认值
	 */
	protected function _set_defaultvalue_taskruntimeinterval() {
		$this->set_defaultkeyandvalue ( self::DBKey_taskruntimeinterval, 0 );
	}

	/**
	 * 任务配置开始时间
	 *
	 * @var string
	 */
	const DBKey_taskstarttime = "taskstarttime";
	/**
	 * 获取 任务配置开始时间
	 */
	public function get_taskstarttime() {
		return $this->getdata ( self::DBKey_taskstarttime );
	}
	/**
	 * 设置 任务配置开始时间
	 *
	 * @param unknown $value
	 */
	public function set_taskstarttime($value) {
		$value = intval ( $value );
		$this->setdata ( self::DBKey_taskstarttime, $value );
	}
	/**
	 * 设置 开始时间 默认值
	 */
	protected function _set_defaultvalue_taskstarttime() {
		$this->set_defaultkeyandvalue ( self::DBKey_taskstarttime, 0 );
	}

	/**
	 * 任务上次运行时间
	 *
	 * @var string
	 */
	const DBKey_tasklastruntime = "tasklastruntime";
	/**
	 * 获取 任务上次运行时间
	 */
	public function get_tasklastruntime() {
		return $this->getdata ( self::DBKey_tasklastruntime );
	}
	/**
	 * 设置 任务上次运行时间
	 *
	 * @param unknown $value
	 */
	public function set_tasklastruntime($value) {
		$value = intval ( $value );
		$this->setdata ( self::DBKey_tasklastruntime, $value );
	}
	/**
	 * 设置 任务上次运行时间 默认值
	 */
	protected function _set_defaultvalue_tasklastruntime() {
		$this->set_defaultkeyandvalue ( self::DBKey_tasklastruntime, 0 );
	}

	/**
	 * 任务名称,唯一名称
	 *
	 * @var string
	 */
	const DBKey_taskname = "taskname";
	/**
	 * 获取 任务名称,唯一名称
	 */
	public function get_taskname() {
		return $this->getdata ( self::DBKey_taskname );
	}
	/**
	 * 设置 任务名称,唯一名称
	 *
	 * @param unknown $value
	 */
	public function set_taskname($value) {
		$value = strval ( $value );
		$this->setdata ( self::DBKey_taskname, $value );
	}
	/**
	 * 设置 任务名称,唯一名称 默认值
	 */
	protected function _set_defaultvalue_taskname() {
		$this->set_defaultkeyandvalue ( self::DBKey_taskname, null );
	}

	/**
	 * 任务调用接口
	 *
	 * @var string
	 */
	const DBKey_taskobj = "taskobj";
	/**
	 * 获取 任务调用接口 dbs_interface_iSchedule
	 */
	public function get_taskobj() {
		return $this->getdata ( self::DBKey_taskobj );
	}
	/**
	 * 设置 任务调用接口
	 *
	 * @param unknown $value
	 */
	public function set_taskobj($value) {
		$value = serialize ( $value );
		$this->setdata ( self::DBKey_taskobj, $value );
	}
	/**
	 * 设置 任务调用接口 默认值
	 */
	protected function _set_defaultvalue_taskobj() {
		$this->set_defaultkeyandvalue ( self::DBKey_taskobj, null );
	}
	function __construct() {
		parent::__construct ( array () );
	}
}