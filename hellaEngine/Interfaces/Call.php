<?php

namespace hellaEngine\Interfaces;

/**
 * 调用接口
 *
 * @author zhipeng
 *
 */
interface Call {
	/**
	 * 实际调用主函数前调用
	 */
	function call_before();

	/**
	 * 实际调用主函数后调用
	 */
	function call_after();
}