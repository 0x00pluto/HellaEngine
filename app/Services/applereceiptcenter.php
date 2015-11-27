<?php

namespace app\Services;

use apps\payverify\dbs\applereceipt\Center;

/**
 * @auther zhipeng
 */
class applereceiptcenter extends Base {
	function __construct() {
		$this->services_enable ( array (
				'getall',
				'verify'
		) );
	}
	protected function get_dbins() {
		return Center::getInstance ();
	}
	protected function get_err_class_name() {
		return "apps\payverify\\err\\" . "err_dbs_applereceipt_center" . "_";
	}
	/**
	 * 获取所有票据
	 *
	 * @return
	 */
	function getall() {
		return $this->get_dbins ()->getall ();
	}

	/**
	 * 校验苹果订单
	 *
	 * @param string $platformid
	 *        	平台di
	 * @param string $orderid
	 *        	订单id
	 * @param string $receipt
	 *        	票据数据
	 * @param integer $rmbnum
	 *        	金额 分
	 * @return \Common\Util\Common_Util_ReturnVar
	 */
	function verify($platformid, $orderid, $receipt, $rmbnum) {
		return $this->get_dbins ()->verify ( $platformid, $orderid, $receipt, $rmbnum );
	}
}