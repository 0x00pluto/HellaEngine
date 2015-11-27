<?php

namespace hellaEngine\Supports\Common\Util;

use constants\constants_time;

/**
 *
 * @package common
 * @subpackage util
 * @author kain
 *
 */
class CommonUtilTime {
	function getWeekNo($time) {
		return floor ( ($time - 316800) / (86400 * 7) );
	}

	// 返回true，表示是小于16岁；false，表示不受16岁限制
	function isSixteen($birthday) {
		$time1 = strtotime ( $birthday );
		$cmpTime = 60 * 60 * 24 * (365 * 16 + 4);

		if ((time () - $time1) <= $cmpTime) {
			return true;
		}

		return false;
	}
	/**
	 * 获取指定时间戳所在的星期的第几天
	 *
	 * @param int $dayNo
	 *        	1-7 星期一 星期天
	 * @param mixted $time
	 *        	时间戳 或者 描述时间的字符串[默认为当前时间]
	 * @return 指定天的零点时间戳
	 */
	public function getDayOfSameWeek($dayNo, $time = 0) {
		$dayNo = intval ( $dayNo );
		if (! ($dayNo >= 1 && $dayNo <= 7)) {
			return false;
		}
		if (! is_numeric ( $time )) {
			$time = strtotime ( $time );
		}
		$time || $time = time ();
		$N = date ( "N", $time );
		$restime = $time - ($N - $dayNo) * 86400;
		$resdate = date ( "Y-m-d 00:00:00", $restime );
		$res = strtotime ( $resdate );
		return $res;
	}

	/**
	 * 获取当前时间
	 *
	 * @return 秒
	 */
	public static function getCurrenttime() {
		list ( $msec, $sec ) = explode ( " ", microtime () );
		return (( float ) $msec + ( float ) $sec);
	}
	private static $gamedays = 0;
	/**
	 * 获取游戏天数,主要是用于游戏的隔天刷新,目前是24点刷新
	 */
	public static function getGameDay() {
		if (self::$gamedays == 0) {
			self::$gamedays = floor ( time () / constants_time::SECONDS_ONE_DAY );
		}
		return self::$gamedays;
	}

	/**
	 * 获取年龄
	 *
	 * @param unknown $birthday
	 *        	'1985-02-01';
	 * @return number
	 */
	public static function getage($birthday) {
		$age = date ( 'Y', time () ) - date ( 'Y', strtotime ( $birthday ) ) - 1;
		if (date ( 'm', time () ) == date ( 'm', strtotime ( $birthday ) )) {

			if (date ( 'd', time () ) > date ( 'd', strtotime ( $birthday ) )) {
				$age ++;
			}
		} elseif (date ( 'm', time () ) > date ( 'm', strtotime ( $birthday ) )) {
			$age ++;
		}
		return $age;
	}
}

?>
