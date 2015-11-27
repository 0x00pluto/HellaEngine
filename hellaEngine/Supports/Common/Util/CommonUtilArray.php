<?php

namespace hellaEngine\Supports\Common\Util;

/**
 * Common_Util_Array
 *
 * @package common
 * @subpackage util
 * @author kain
 *
 */
class CommonUtilArray {

	/**
	 * clone数组
	 *
	 * @param
	 *        	$arr
	 * @return array
	 */
	static function clone_array($arr) {
		if (is_array ( $arr )) {
			$newarray = array ();
			foreach ( $arr as $key => $value ) {
				if (is_array ( $value )) {
					$newarray [$key] = self::clone_array ( $value );
				} elseif (is_object ( $value )) {
					$newarray [$key] = clone $value;
				} else {
					$newarray [$key] = $value;
				}
			}
			return $newarray;
		} else {

			return $arr;
		}
	}

	/**
	 * 数组排重
	 *
	 * @param array $array
	 * @return array
	 */
	static function array_unique(array $array) {
		$arr = array ();
		foreach ( $array as $key => $value ) {
			if (! in_array ( $value, $arr )) {
				$arr [$key] = $value;
			}
		}
		return $arr;
	}

	/**
	 * 通过值删除数组
	 *
	 * @param array $array
	 * @param array $delete_values
	 * @return array
	 */
	static function array_remove_by_values(array $array, array $delete_values) {
		foreach ( $delete_values as $del_value ) {
			if (($key = array_search ( $del_value, $array )) !== false) {
				unset ( $array [$key] );
			}
		}
		return $array;
	}

	/**
	 * 通过值删除数组元素
	 *
	 * @param unknown $array
	 * @param unknown $delete_value
	 * @return unknown
	 */
	static function array_remove_by_value($array, $delete_value) {
		if (($key = array_search ( $delete_value, $array )) !== false) {
			unset ( $array [$key] );
		}
		return $array;
	}

	/**
	 * 比较数组值是否相同
	 *
	 * @param array $a
	 * @param array $b
	 * @return boolean
	 */
	static function array_equal_values(array $a, array $b) {
		return ! array_diff ( $a, $b ) && ! array_diff ( $b, $a );
	}

	/**
	 *
	 * 获取数组字段
	 *
	 * @param array $arr
	 * @param string $key
	 * @param string $defaultvalue
	 *        	默认值,当key不存在的时候 返回默认值
	 * @return CommonUtilValue
	 */
	static function getvalue($arr, $key, $defaultvalue = NULL) {
		if (array_key_exists_faster ( $key, $arr )) {
			return CommonUtilValue::build ( $arr [$key] );
		}
		return CommonUtilValue::build ( $defaultvalue );
	}

	/**
	 * in_array 替代函数
	 *
	 * @param any $item
	 * @param array $array
	 */
	static function in_array($item, $array) {
		if (empty ( $array ) || ! is_array ( $array )) {
			return false;
		}
		if (empty ( $item )) {
			return false;
		}
		$flipArray = array_flip ( $array );
		return isset ( $flipArray [$item] );
	}
}

?>