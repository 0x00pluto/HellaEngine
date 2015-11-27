<?php

namespace app\payverify\err;

class err_dbs_notice_center_check {
	/**
	 * 充值数据不存在
	 *
	 * @var unknown
	 */
	const RECHARGE_DATA_NOT_EXIST = 1;
	/**
	 * 已经验证过了
	 *
	 * @var unknown
	 */
	const ALREADY_VERIFYED = 2;
}
class err_dbs_notice_center_recordrechargedata {
	/**
	 * 充值数据已经存在
	 *
	 * @var unknown
	 */
	const RECHARGE_DATA_EXIST = 1;
}