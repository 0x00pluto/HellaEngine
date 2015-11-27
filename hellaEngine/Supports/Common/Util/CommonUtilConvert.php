<?php

namespace hellaEngine\Supports\Common\Util;

/**
 * CommonUtilConvert
 *
 * @package common
 * @subpackage util
 * @author kain
 *
 */
/**
 * UTF-8乱码转GBK,用于各种怀疑是UTF-8编码的字符串转GBK。
 * 支持Unicode编码的字符串，如"%u4E24%u6027" => "两性"
 * "&#20020;&#27778;&#20256;&#23186;&#32593;" => "临沂传媒网"
 * "&#x4E24;&#x6027;" => "两性"
 * ShenXinyu
 * 12/06/2005
 */
class CommonUtilConvert {
	var $special = array (
			array (
					1,
					1,
					1,
					0,
					1,
					1,
					0,
					0,
					1,
					1,
					1,
					1,
					1,
					1,
					1,
					0,
					0,
					1,
					1,
					1,
					1,
					1,
					0,
					1,
					1,
					1,
					1,
					1,
					1,
					1,
					1
			),
        /* 隆垄拢  楼娄    漏陋芦卢颅庐炉    虏鲁麓碌露  赂鹿潞禄录陆戮驴 */
		array (
					0,
					1,
					1,
					1,
					1,
					1,
					1,
					0,
					0,
					0,
					1,
					0,
					0,
					1,
					1,
					1,
					1,
					0,
					0,
					1,
					1,
					1,
					0,
					1,
					0,
					0,
					1,
					0,
					1,
					1,
					1
			)
		/*   芒茫盲氓忙莽      毛    卯茂冒帽    么玫枚  酶    没  媒镁每 */
	);
	function unescape($str) {
		preg_match_all ( "/(?:%u[0-9A-Fa-f]{4})|&#x[0-9A-Fa-f]{4};|&#\d+;|.+|\n/U", $str, $r );
		$ar = $r [0];
		foreach ( $ar as $k => $v ) {
			if (substr ( $v, 0, 2 ) == "%u") {
				$ar [$k] = iconv ( "UCS-2BE", "UTF-8", pack ( "H4", substr ( $v, - 4 ) ) );
			} elseif (substr ( $v, 0, 3 ) == "&#x") {
				$ar [$k] = iconv ( "UCS-2BE", "UTF-8", pack ( "H4", substr ( $v, 3, - 1 ) ) );
			} elseif (substr ( $v, 0, 2 ) == "&#") {
				$ar [$k] = iconv ( "UCS-2BE", "UTF-8", pack ( "n", substr ( $v, 2, - 1 ) ) );
			}
		}
		return join ( "", $ar );
	}
	function contain_special($str) {
		$strlen = strlen ( $str );
		for($i = 0; $i < $strlen - 1;) {
			if (ord ( $str [$i] ) & 0x80 != 0) {
				if ($i + 1 < $strlen) {
					if (ord ( $str [$i] ) >= 0xc2 && ord ( $str [$i] ) <= 0xc3) {
						if (ord ( $str [$i + 1] ) >= 0xa1 && ord ( $str [$i + 1] ) <= 0xbf) {
							if ($this->special [ord ( $str [$i] ) - 0xc2] [ord ( $str [$i + 1] ) - 0xa1])
								return TRUE;
							else
								$i = $i + 2;
						}
					}
				}
			}
			$i ++;
		}

		return FALSE;
	}
	function junk2gbk($str, $safe = true, $cut = 0) {
		$tempstr = $this->unescape ( $str );
		if ($cut) {
			$tempstr = substr ( $tempstr, 0, 0 - $cut );
		}
		$gbkstr = iconv ( SYS_CHARSET, DB_CHARSET, $tempstr );
		if (CommonUtilConvert::iconvFailure ( $gbkstr, $tempstr )) {
			$cut ++;
			if ($cut < 5 && strlen ( $str ) > 4) { // 截断末尾的1到4个字符在进行转换
				return $this->junk2gbk ( $str, $safe, $cut );
			}
			// 判断是否包含特殊字符，包含的不进行转码
			if ($this->contain_special ( $tempstr ) == TRUE) {
				return $safe ? Common_Util_HtmlParse::forbidScript ( $tempstr ) : $tempstr;
			}
			$gbkstr = iconv ( SYS_CHARSET, "8859_1", $tempstr );
			if (CommonUtilConvert::iconvFailure ( $gbkstr, $tempstr )) {
				return $safe ? Common_Util_HtmlParse::forbidScript ( $tempstr ) : $tempstr;
			}
		} else {
			/*
			 * 转码前后长度一样的，认为不是UTF-8编码
			 * (gb18030含有部分4字节字符，转成utf-8长度有可能一样)
			 * if (strlen($gbkstr) == strlen($tempstr))
			 * {
			 * return $safe ? Common_Util_HtmlParse::forbidScript($tempstr) : $tempstr;
			 * }
			 */

			// 部分转码认为不是UTF-8编码
			if (strlen ( $gbkstr ) && strstr ( $tempstr, $gbkstr ) !== FALSE) {
				return $safe ? Common_Util_HtmlParse::forbidScript ( $tempstr ) : $tempstr;
			}
		}

		return $safe ? Common_Util_HtmlParse::forbidScript ( $gbkstr ) : $gbkstr;
	}
	function iconvFailure($ret, $str) {
		return $ret === false || ($ret === "" && strlen ( $str ));
	}
	static function getSysStr($input) {
		return iconv ( DB_CHARSET, SYS_CHARSET . "//IGNORE", $input );
	}
	static function getDBStr($input) {
		return iconv ( SYS_CHARSET, DB_CHARSET . "//IGNORE", $input );
	}

	// 因为junk2gbk方法会丢英文字符或数字后面的汉字，所以改写了一个转换函数，目前用于rshare组件.
	function junk2gbkRshare($value) {
		$gbklen = iconv_strlen ( $value, 'GB18030' );
		$utf8len = iconv_strlen ( $value, 'UTF-8' );
		$oldValue = $value;
		if ($gbklen == $utf8len and $utf8len > 0) { // 目前发现的特殊字符串,不用转码: ‘什么sdfsd’
			$value = $oldValue;
		} else if ($utf8len) { // utf8编码
			$value = iconv ( "UTF-8", "GB18030", $value );
		} else if ($gbklen === false) { // 即不是UTF8也不是GB编码
			$value = $this->junk2gbk ( $value );
		}
		// gbk无须转码

		if (empty ( $value )) { // 万一判断错误转码失败（有可能GBK串里面包含几个UTF8字符），用原值。
			$value = $oldValue;
		}
		return $value;
	}

	// 不丢失字符转gbk，不能转的字符用&#174;这种unicode形式表示。
	// 输出到页面时要防止&#174;这种字符做htmlspecialchars，需要的话先转utf8然后unescape再htmlspecialchars
	function tryGetGBK($name) {
		$tostr = "";
		for($i = 0; $i < strlen ( $name ); $i ++) {
			$curbin = ord ( substr ( $name, $i, 1 ) );
			if ($curbin < 0x80) {
				$tostr .= substr ( $name, $i, 1 );
			} elseif ($curbin < bindec ( "11000000" )) {
				$str = substr ( $name, $i, 1 );
				$tostr .= "&#" . ord ( $str ) . ";";
			} elseif ($curbin < bindec ( "11100000" )) {
				$str = substr ( $name, $i, 2 );
				$tostr .= "&#" . self::getUnicodeChar ( $str ) . ";";
				$i += 1;
			} elseif ($curbin < bindec ( "11110000" )) {
				$str = substr ( $name, $i, 3 );
				$gstr = iconv ( "UTF-8", "GB18030", $str );
				if (! $gstr) {
					$tostr .= "&#" . self::getUnicodeChar ( $str ) . ";";
				} else {
					$tostr .= $gstr;
				}
				$i += 2;
			} elseif ($curbin < bindec ( "11111000" )) {
				$str = substr ( $name, $i, 4 );
				$tostr .= "&#" . self::getUnicodeChar ( $str ) . ";";
				$i += 3;
			} elseif ($curbin < bindec ( "11111100" )) {
				$str = substr ( $name, $i, 5 );
				$tostr .= "&#" . self::getUnicodeChar ( $str ) . ";";
				$i += 4;
			} else {
				$str = substr ( $name, $i, 6 );
				$tostr .= "&#" . self::getUnicodeChar ( $str ) . ";";
				$i += 5;
			}
		}
		return $tostr;
	}
	function getUnicodeChar($str) {
		$temp = "";
		for($i = 0; $i < strlen ( $str ); $i ++) {
			$x = decbin ( ord ( substr ( $str, $i, 1 ) ) );
			if ($i == 0) {
				$s = strlen ( $str ) + 1;
				$temp .= substr ( $x, $s, 8 - $s );
			} else {
				$temp .= substr ( $x, 2, 6 );
			}
		}
		return bindec ( $temp );
	}
	function indexOf($arr, $e) {
		for($i = 0; $i < count ( $arr ); $i ++) {
			if ($arr [$i] == $e) {
				return $i;
			}
		}
	}
	function convert($str, $n1, $n2) {
		$arr_16 = array (
				'0000',
				'0001',
				'0010',
				'0011',
				'0100',
				'0101',
				'0110',
				'0111',
				'1000',
				'1001',
				'1010',
				'1011',
				'1100',
				'1101',
				'1110',
				'1111'
		);
		$arr_32 = array (
				'00000',
				'00001',
				'00010',
				'00011',
				'00100',
				'00101',
				'00110',
				'00111',
				'01000',
				'01001',
				'01010',
				'01011',
				'01100',
				'01101',
				'01110',
				'01111',
				'10000',
				'10001',
				'10010',
				'10011',
				'10100',
				'10101',
				'10110',
				'10111',
				'11000',
				'11001',
				'11010',
				'11011',
				'11100',
				'11101',
				'11110',
				'11111'
		);
		$arr_64 = array (
				'000000',
				'000001',
				'000010',
				'000011',
				'000100',
				'000101',
				'000110',
				'000111',
				'001000',
				'001001',
				'001010',
				'001011',
				'001100',
				'001101',
				'001110',
				'001111',
				'010000',
				'010001',
				'010010',
				'010011',
				'010100',
				'010101',
				'010110',
				'010111',
				'011000',
				'011001',
				'011010',
				'011011',
				'011100',
				'011101',
				'011110',
				'011111',
				'100000',
				'100001',
				'100010',
				'100011',
				'100100',
				'100101',
				'100110',
				'100111',
				'101000',
				'101001',
				'101010',
				'101011',
				'101100',
				'101101',
				'101110',
				'101111',
				'110000',
				'110001',
				'110010',
				'110011',
				'110100',
				'110101',
				'110110',
				'110111',
				'111000',
				'111001',
				'111010',
				'111011',
				'111100',
				'111101',
				'111110',
				'111111'
		);
		$char_arr = array (
				'0',
				'1',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7',
				'8',
				'9',
				'a',
				'b',
				'c',
				'd',
				'e',
				'f',
				'g',
				'h',
				'i',
				'j',
				'k',
				'l',
				'm',
				'n',
				'o',
				'p',
				'q',
				'r',
				's',
				't',
				'u',
				'v',
				'w',
				'x',
				'y',
				'z',
				'A',
				'B',
				'C',
				'D',
				'E',
				'F',
				'G',
				'H',
				'I',
				'J',
				'K',
				'L',
				'M',
				'N',
				'O',
				'P',
				'Q',
				'R',
				'S',
				'T',
				'U',
				'V',
				'W',
				'X',
				'Y',
				'Z',
				'!',
				'@'
		);
		$result_str = '';
		$s = '';
		$a = '';
		switch ($n1) {
			case 16 :
				$arr = $arr_16;
				break;
			case 32 :
				$arr = $arr_32;
				break;
			case 64 :
				$arr = $arr_64;
				break;
		}
		switch ($n2) {
			case 16 :
				$arrt = $arr_16;
				$b = 4;
				break;
			case 32 :
				$arrt = $arr_32;
				$b = 5;
				break;
			case 64 :
				$arrt = $arr_64;
				$b = 6;
				break;
		}
		for($i = 0; $i < strlen ( $str ); $i ++) {
			$s .= $arr [$this->indexOf ( $char_arr, $str [$i] )];
		}
		for($i = 0; $i < $b - strlen ( $s ) % $b; $i ++) {
			$a .= '0';
		}
		$s = $a . $s;
		for($i = 0; $i < strlen ( $s ); $i += $b) {
			$result_str .= $char_arr [$this->indexOf ( $arrt, substr ( $s, $i, $b ) )];
		}
		for($i = 0; $i < strlen ( $result_str ); $i ++) {
			if ($result_str [$i] != '0') {
				return substr ( $result_str, $i );
			}
		}
	}
}

?>
