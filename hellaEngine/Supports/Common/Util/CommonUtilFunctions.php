<?php

namespace hellaEngine\Supports\Common\Util;

class MissingArgumentException extends \Exception {
}
/**
 * 函数回调类
 *
 * @author zhipeng
 *
 */
class CommonUtilFunctions {
	/**
	 * 调用全局函数
	 *
	 * @param string $method
	 *        	函数名称
	 * @param array $arr
	 * @throws MissingArgumentException
	 */
	static function call_user_func_named_array($method, array $arr = []) {
		$ref = new \ReflectionFunction ( $method );
		$params = [ ];
		foreach ( $ref->getParameters () as $p ) {
			if ($p->isOptional ()) {
				if (isset ( $arr [$p->name] )) {
					$params [] = $arr [$p->name];
				} else {
					$params [] = $p->getDefaultValue ();
				}
			} else if (isset ( $arr [$p->name] )) {
				$params [] = $arr [$p->name];
			} else {
				throw new MissingArgumentException ( "Missing parameter $p->name" );
			}
		}
		return $ref->invokeArgs ( $params );
	}

	/**
	 * 调用
	 *
	 * @param string $classname
	 *        	类名
	 * @param string $functionname
	 *        	函数名称
	 * @param array $arr
	 *        	参数列表 key=>value
	 * @return mixed
	 */
	static function call_class_func_named_array($classname, $functionname, array $arr = []) {
		return CommonUtilFunctions::call_class_func_named_object_array ( $classname, $functionname, new $classname (), $arr );
	}
	/**
	 *
	 * @param string $classname
	 * @param string $functionname
	 * @param mixed $classobj
	 *        	instanceof $classname
	 * @param array $arr
	 *        	参数列表 key=>value
	 * @throws MissingArgumentException
	 * @return mixed
	 */
	static function call_class_func_named_object_array($classname, $functionname, $classobj, array $arr = []) {
		$ref = new \ReflectionMethod ( $classname, $functionname );
		$params = [ ];
		foreach ( $ref->getParameters () as $p ) {
			if ($p->isOptional ()) {
				if (isset ( $arr [$p->name] )) {
					$params [] = $arr [$p->name];
				} else {
					$params [] = $p->getDefaultValue ();
				}
			} else if (isset ( $arr [$p->name] )) {
				$params [] = $arr [$p->name];
			} else {
				throw new MissingArgumentException ( "Missing parameter $p->name" );
			}
		}

		return $ref->invokeArgs ( $classobj, $params );
	}
}
?>