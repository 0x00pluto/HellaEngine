<?php

namespace hellaEngine\err;

/**
 *
 * @author zhipeng
 *
 */
class err_service_gateway_call {
	/**
	 * 服务类没有找到
	 *
	 * @var unknown
	 */
	const SERVICE_CLASS_NOT_FOUND = 1;

	/**
	 * 服务类类型错误
	 *
	 * @var unknown
	 */
	const SERVER_CLASS_TYPE_ERROR = 2;

	/**
	 * verify 错误
	 *
	 * @var unknown
	 */
	const VERIFY_IS_ERROR = 3;

	/**
	 * 用户数据没有找到
	 *
	 * @var unknown
	 */
	const USER_DATA_ERROR = 4;

	/**
	 * verify 为空
	 *
	 * @var unknown
	 */
	const VERIFY_IS_EMPTY = 5;

	/**
	 * 参数错误
	 *
	 * @var unknown
	 */
	const ARGUMENT_ERROR = 6;
	/**
	 * 不能调用
	 *
	 * @var unknown
	 */
	const NO_CALLABLE = 7;
}