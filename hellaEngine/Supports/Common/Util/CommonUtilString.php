<?php

namespace hellaEngine\Supports\Common\Util;

class CommonUtilString {
	/**
	 * 计算UTF8长度
	 *
	 * @param string $string
	 * @return number
	 */
	static function utf8_strlen($string = null) {
		if (empty ( $string )) {
			return 0;
		}
		return iconv_strlen ( $string, "UTF-8" );
	}
	/**
	 * 删除所有空格
	 *
	 * @param unknown $str
	 * @return mixed
	 */
	static function tirmall($str) {
		return preg_replace ( '# #', '', $str );
	}
}