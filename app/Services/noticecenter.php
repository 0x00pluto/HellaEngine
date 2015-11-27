<?php

namespace app\Services;

use Common\Util\Common_Util_ReturnVar;
use apps\payverify\dbs\notice\Center;

/**
 * @auther zhipeng
 */
class noticecenter extends Base {
	function __construct() {
		$this->services_enable ( array (
				'getall',
				'check'
		) );
	}
	protected function get_dbins() {
		return Center::getInstance ();
	}
	protected function get_err_class_name() {
		return "apps\\payverify\\err\\" . "err_dbs_notice_center" . "_";
	}

	/**
	 * 获取所有的订单
	 *
	 * @return \Common\Util\Common_Util_ReturnVar
	 */
	function getall() {
		return $this->get_dbins ()->getall ();
	}
	/**
	 * 校验订单
	 *
	 * @param unknown $orderid
	 * @return \Common\Util\Common_Util_ReturnVar
	 */
	function check($orderid) {
		return $this->get_dbins ()->check ( $orderid );
	}
}