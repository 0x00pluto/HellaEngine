<?php

namespace hellaEngine\Supports\Common\Util;

/**
 * 随机函数
 *
 * @author zhipeng
 *
 */
class CommonUtilRandom {
	/**
	 * 权重随机
	 *
	 * @param array $weight
	 *        	权重 例如array('a'=>10,'b'=>20,'c'=>50)
	 * @return string
	 */
	public static function RandomWithWeight($weight = array()) {
		if (empty ( $weight )) {
			return null;
		}
		$totalweight = count ( $weight );
		if ($totalweight <= 0) {
			return null;
		}
		$roll = mt_rand ( 1, array_sum ( $weight ) );
		// $roll = rand ( 1, array_sum ( $weight ) );
		$_tmpW = 0;
		$rollkey = null;
		foreach ( $weight as $k => $v ) {
			$v = intval ( $v );
			if ($v <= 0) {
				continue;
			}

			$min = $_tmpW;
			$_tmpW += intval ( $v );
			$max = $_tmpW;
			if ($roll > $min && $roll <= $max) {
				$rollkey = $k;
				break;
			}
		}
		return $rollkey;
	}

	/**
	 * 随机时间
	 *
	 * @param string $start_time
	 *        	'2015-01-01 00:00:00'
	 * @param string $end_time'2015-06-01
	 *        	00:00:00'
	 * @return string
	 */
	static function rand_time($start_time, $end_time) {
		$start_time = strtotime ( $start_time );
		$end_time = strtotime ( $end_time );
		return date ( 'Y-m-d H:i:s', mt_rand ( $start_time, $end_time ) );
	}
}