<?php

namespace app\payverify\dbs\notice;
use app\payverify\dbs\DbsBaseDataCell;


/**
 *
 * @author zhipeng
 *
 */
class RechargeData extends DbsBaseDataCell {
	/**
	 * 应用id
	 *
	 * @var string
	 */
	const DBKey_appid = "appid";
	/**
	 * 获取 应用id
	 */
	public function get_appid() {
		return $this->getdata ( self::DBKey_appid );
	}
	/**
	 * 设置 应用id
	 *
	 * @param string $value
	 */
	public function set_appid($value) {
		$value = strval ( $value );
		$this->setdata ( self::DBKey_appid, $value );
	}
	protected function _set_defaultvalue_appid() {
		$this->set_defaultkeyandvalue ( self::DBKey_appid, '' );
	}

	/**
	 * 平台id
	 *
	 * @var string
	 */
	const DBKey_platformid = "platformid";
	/**
	 * 获取 平台id
	 */
	public function get_platformid() {
		return $this->getdata ( self::DBKey_platformid );
	}
	/**
	 * 设置 平台id
	 *
	 * @param string $value
	 */
	public function set_platformid($value) {
		$value = strval ( $value );
		$this->setdata ( self::DBKey_platformid, $value );
	}
	protected function _set_defaultvalue_platformid() {
		$this->set_defaultkeyandvalue ( self::DBKey_platformid, '' );
	}

	/**
	 * 订单编号
	 *
	 * @var string
	 */
	const DBKey_orderid = "orderid";
	/**
	 * 获取 订单编号
	 */
	public function get_orderid() {
		return $this->getdata ( self::DBKey_orderid );
	}
	/**
	 * 设置 订单编号
	 *
	 * @param string $value
	 */
	public function set_orderid($value) {
		$value = strval ( $value );
		$this->setdata ( self::DBKey_orderid, $value );
	}
	protected function _set_defaultvalue_orderid() {
		$this->set_defaultkeyandvalue ( self::DBKey_orderid, '' );
	}

	/**
	 * 票据的唯一id,防止同一个票据重复验证
	 *
	 * @var string
	 */
	const DBKey_unique_identifier = "unique_identifier";
	/**
	 * 获取 票据的唯一id,防止同一个票据重复验证
	 */
	public function get_unique_identifier() {
		return $this->getdata ( self::DBKey_unique_identifier );
	}
	/**
	 * 设置 票据的唯一id,防止同一个票据重复验证
	 *
	 * @param string $value
	 */
	public function set_unique_identifier($value) {
		$value = strval ( $value );
		$this->setdata ( self::DBKey_unique_identifier, $value );
	}
	protected function _set_defaultvalue_unique_identifier() {
		$this->set_defaultkeyandvalue ( self::DBKey_unique_identifier, '' );
	}

	/**
	 * 金额单位分
	 *
	 * @var string
	 */
	const DBKey_money = "money";
	/**
	 * 获取 金额单位分
	 */
	public function get_money() {
		return $this->getdata ( self::DBKey_money );
	}
	/**
	 * 设置 金额单位分
	 *
	 * @param int $value
	 */
	public function set_money($value) {
		$value = intval ( $value );
		$this->setdata ( self::DBKey_money, $value );
	}
	protected function _set_defaultvalue_money() {
		$this->set_defaultkeyandvalue ( self::DBKey_money, 0 );
	}

	/**
	 * 充值时间
	 *
	 * @var string
	 */
	const DBKey_rechargetime = "rechargetime";
	/**
	 * 获取 充值时间
	 */
	public function get_rechargetime() {
		return $this->getdata ( self::DBKey_rechargetime );
	}
	/**
	 * 设置 充值时间
	 *
	 * @param int $value
	 */
	public function set_rechargetime($value) {
		$value = intval ( $value );
		$this->setdata ( self::DBKey_rechargetime, $value );
	}
	protected function _set_defaultvalue_rechargetime() {
		$this->set_defaultkeyandvalue ( self::DBKey_rechargetime, 0 );
	}

	/**
	 * 是否完成校验
	 *
	 * @var string
	 */
	const DBKey_iscompleteverify = "iscompleteverify";
	/**
	 * 获取 是否完成校验
	 */
	public function get_iscompleteverify() {
		return $this->getdata ( self::DBKey_iscompleteverify );
	}
	/**
	 * 设置 是否完成校验
	 *
	 * @param bool $value
	 */
	public function set_iscompleteverify($value) {
		$value = boolval ( $value );
		$this->setdata ( self::DBKey_iscompleteverify, $value );
	}
	protected function _set_defaultvalue_iscompleteverify() {
		$this->set_defaultkeyandvalue ( self::DBKey_iscompleteverify, false );
	}

	/**
	 * 完成校验的日期
	 *
	 * @var string
	 */
	const DBKey_completetimestamp = "completetimestamp";
	/**
	 * 获取 完成校验的日期
	 */
	public function get_completetimestamp() {
		return $this->getdata ( self::DBKey_completetimestamp );
	}
	/**
	 * 设置 完成校验的日期
	 *
	 * @param int $value
	 */
	public function set_completetimestamp($value) {
		$value = intval ( $value );
		$this->setdata ( self::DBKey_completetimestamp, $value );
	}
	/**
	 * 设置 完成校验的日期 默认值
	 */
	protected function _set_defaultvalue_completetimestamp() {
		$this->set_defaultkeyandvalue ( self::DBKey_completetimestamp, 0 );
	}

	/**
	 * 商品id
	 *
	 * @var string
	 */
	const DBKey_goodsid = "goodsid";
	/**
	 * 获取 商品id
	 */
	public function get_goodsid() {
		return $this->getdata ( self::DBKey_goodsid );
	}
	/**
	 * 设置 商品id
	 *
	 * @param string $value
	 */
	public function set_goodsid($value) {
		$value = strval ( $value );
		$this->setdata ( self::DBKey_goodsid, $value );
	}
	/**
	 * 设置 商品数量 默认值
	 */
	protected function _set_defaultvalue_goodsid() {
		$this->set_defaultkeyandvalue ( self::DBKey_goodsid, '' );
	}

	/**
	 * 商品数量
	 *
	 * @var string
	 */
	const DBKey_goodsnum = "goodsnum";
	/**
	 * 获取 商品数量
	 */
	public function get_goodsnum() {
		return $this->getdata ( self::DBKey_goodsnum );
	}
	/**
	 * 设置 商品数量
	 *
	 * @param int $value
	 */
	public function set_goodsnum($value) {
		$value = intval ( $value );
		$this->setdata ( self::DBKey_goodsnum, $value );
	}
	/**
	 * 设置 商品数量 默认值
	 */
	protected function _set_defaultvalue_goodsnum() {
		$this->set_defaultkeyandvalue ( self::DBKey_goodsnum, 0 );
	}

	/**
	 * 扩展信息
	 *
	 * @var string
	 */
	const DBKey_extinfo = "extinfo";
	/**
	 * 获取 扩展信息
	 */
	public function get_extinfo() {
		return $this->getdata ( self::DBKey_extinfo );
	}
	/**
	 * 设置 扩展信息
	 *
	 * @param mixed $value
	 */
	public function set_extinfo($value) {
		// $value = strval($value);
		$this->setdata ( self::DBKey_extinfo, $value );
	}
	/**
	 * 设置 扩展信息 默认值
	 */
	protected function _set_defaultvalue_extinfo() {
		$this->set_defaultkeyandvalue ( self::DBKey_extinfo, array () );
	}

	/**
	 * 表名
	 *
	 * @var string
	 */
	const DBKey_tablename = "rechargedata";
	/**
	 * 完成校验
	 */
	public function complete_verify() {
		$this->set_iscompleteverify ( true );
		$this->set_completetimestamp ( time () );
	}
}