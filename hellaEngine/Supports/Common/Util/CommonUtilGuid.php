<?php

namespace hellaEngine\Supports\Common\Util;

class CommonUtilGuid {
	static function uuid($prefix = '') {
		// functionsDump(uniqid(mt_rand(), true));
		// $chars = md5 ( uniqid ( mt_rand (), true ) );
		// $uuid = substr ( $chars, 0, 8 ) . '-';
		// $uuid .= substr ( $chars, 8, 4 ) . '-';
		// $uuid .= substr ( $chars, 12, 4 ) . '-';
		// $uuid .= substr ( $chars, 16, 4 ) . '-';
		// $uuid .= substr ( $chars, 20, 12 );
		return $prefix . self::create_guid ( $prefix );
	}
	static function create_guid($namespace = '') {
		$guid = '';
		$uid = uniqid ( "", true );
		$data = $namespace;
		$data .= $_SERVER ['REQUEST_TIME'];
		$data .= $_SERVER ['HTTP_USER_AGENT'];
		$data .= $_SERVER ['LOCAL_ADDR'];
		$data .= $_SERVER ['LOCAL_PORT'];
		$data .= $_SERVER ['REMOTE_ADDR'];
		$data .= $_SERVER ['REMOTE_PORT'];
		$hash = hash ( 'ripemd128', $uid . $guid . md5 ( $data ) );
		$guid = substr ( $hash, 0, 8 ) . '-' . substr ( $hash, 8, 4 ) . '-' . substr ( $hash, 12, 4 ) . '-' . substr ( $hash, 16, 4 ) . '-' . substr ( $hash, 20, 12 );
		return $guid;
	}
	static function gen_userid() {
		return self::uuid ( "userid-" );
	}
	static function gen_verify() {
		return self::uuid ( "verify-" );
	}
	/**
	 * 生成随机密码
	 *
	 * @return string
	 */
	static function gen_password() {
		return self::uuid ( "pwd-" );
	}
	/**
	 * 生成道具id
	 *
	 * @return string
	 */
	static function gen_itemid() {
		return self::uuid ( "itemid-" );
	}

	/**
	 * 生成仓库位置
	 */
	static function gen_warehousepos() {
		return self::uuid ( "pos-" );
	}
	/**
	 * 生成建筑id
	 *
	 * @return string
	 */
	static function gen_buildingid() {
		return self::uuid ( "building-" );
	}
	/**
	 * 生成请求guid
	 *
	 * @return string
	 */
	static function gen_friend_request() {
		return self::uuid ( "friendrequest-" );
	}
	static function gen_visitor() {
		return self::uuid ( "visitor-" );
	}
	/**
	 * 厨师id
	 *
	 * @return string
	 */
	static function gen_chefguid() {
		return self::uuid ( "chefid-" );
	}

	/**
	 * 生产邮件id
	 */
	static function gen_mailid() {
		return self::uuid ( "mailid-" );
	}
	/**
	 * 生成邮件附属操作id
	 */
	static function gen_attachactoinid() {
		return self::uuid ( "mailattachactionid-" );
	}
	/**
	 * 生成红包id
	 *
	 * @return string
	 */
	static function gen_neighboorhoodgiftpackageid() {
		return self::uuid ( "gift-" );
	}
	/**
	 * 生产充值id
	 *
	 * @return string
	 */
	static function gen_recharge_orderid() {
		return self::uuid ( "orderid-" );
	}
	/**
	 * 生成公告id
	 */
	static function gen_notice_guid() {
		return self::uuid ( "notice-" );
	}

	/**
	 * 生成机器人用户名
	 *
	 * @return string
	 */
	static function gen_robot_username() {
		return self::uuid ( "robot_username-" );
	}
	/**
	 * 生成交易id
	 */
	static function gen_trade_guid() {
		return self::uuid ( "tradeid-" );
	}
	/**
	 * 宝箱id
	 *
	 * @return string
	 */
	static function gen_box_guid() {
		return self::uuid ( "boxid-" );
	}
	/**
	 * 老虎机id
	 */
	static function gen_superslotmachine_guid() {
		return self::uuid ( "superslotmachine-" );
	}

	/**
	 * 生成群组邀请码
	 *
	 * @return string
	 */
	static function gen_group_inviteguid() {
		return self::uuid ( "groupinviteguid-" );
	}
}