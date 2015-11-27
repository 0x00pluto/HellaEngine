<?php

namespace hellaEngine\Supports\Common\Util;

/**
 *
 * @package common
 * @subpackage util
 * @author kain
 *
 */
class CommonUtilObject {
	public static function std_class_object_to_array($stdclassobject) {
		$array = array ();
		$_array = is_object ( $stdclassobject ) ? get_object_vars ( $stdclassobject ) : $stdclassobject;
		foreach ( $_array as $key => $value ) {
			$value = (is_array ( $value ) || is_object ( $value )) ? CommonUtilObject::std_class_object_to_array ( $value ) : $value;
			$array [$key] = $value;
		}
		return $array;
	}

	/**
	 * 检查容器中是否有 std_class_object 对象
	 *
	 * @param unknown $any
	 * @return boolean
	 */
	public static function check_has_std_class_object($any) {
		if (is_array ( $any )) {
			foreach ( $any as $value ) {
				$succ = self::check_has_std_class_object ( $value );
				if ($succ) {
					return $succ;
				}
			}
		} else {
			return is_object ( $any );
		}
	}
}
?>