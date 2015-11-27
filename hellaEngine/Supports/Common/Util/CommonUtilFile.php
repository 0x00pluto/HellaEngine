<?php

namespace hellaEngine\Supports\Common\Util;

class CommonUtilFile {
	static function traverse($path = ".") {
		return self::_traverse ( $path );
	}
	private static function _traverse($path) {
		$files = array ();
		$current_dir = opendir ( $path ); // opendir()返回一个目录句柄,失败返回false
		while ( ($file = readdir ( $current_dir )) !== false ) { // readdir()返回打开目录句柄中的一个条目
			$sub_dir = $path . DIRECTORY_SEPARATOR . $file; // 构建子目录路径
			if ($file == '.' || $file == '..') {
				continue;
			} else if (is_dir ( $sub_dir )) { // 如果是目录,进行递归
				$subfiles = self::_traverse ( $sub_dir );
				$files = array_merge ( $files, $subfiles );
			} else { // 如果是文件,直接输出
			         // $files[]
				$files [] = $file;
			}
		}

		return $files;
	}
}