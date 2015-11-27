<?php

namespace app\payverify\dbs\notice;

use apps\payverify\err\err_dbs_notice_center_check;
use apps\payverify\err\err_dbs_notice_center_recordrechargedata;
use hellaEngine\Supports\Common\Util\CommonUtilReturnVar;
use hellaEngine\Data\DataBase\DBPools;


/**
 * 说明
 * 2015年8月6日 下午3:09:36
 *
 * @author zhipeng
 *
 */
class Center
{

    /**
     * singleton
     */
    private static $_instance;

    private function __construct()
    {
        // echo 'This is a Constructed method;';
    }

    public function __clone()
    {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }

    /**
     *
     * @return Center
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof static)) {
            self::$_instance = new static ();
        }
        return self::$_instance;
    }

    /**
     * 获取所有的订单
     *
     * @return CommonUtilReturnVar
     */
    function getall()
    {
        $retCode = 0;
        $retCode_Str = 'SUCC';
        $data = array();
        // class err_dbs_noticecenter_getall{}

        $db = DBPools::default_Db_pools()->dbconnect();
        $ret = $db->query(RechargeData::DBKey_tablename);

        $datacell = new RechargeData ();
        foreach ($ret as $key => $value) {
            $datacell->fromArray($value);
        }

        // functionsDump ( $ret );
        // code

        succ:
        return CommonUtilReturnVar::Ret(true, $retCode, $data, $retCode_Str);
        failed:
        return CommonUtilReturnVar::Ret(false, $retCode, $data, $retCode_Str);
    }

    /**
     * 接收充值数据
     *
     * @param RechargeData $rechargeData
     * @return CommonUtilReturnVar
     */
    function recordrechargedata(RechargeData $rechargeData)
    {
        $retCode = 0;
        $retCode_Str = 'SUCC';
        $data = array();
        // class err_dbs_notice_center_recordrechargedata{}

        $oldRechargeData = $this->get_rechargedata($rechargeData->get_orderid(), $rechargeData->get_unique_identifier());
        if (!is_null($oldRechargeData)) {
            $retCode = err_dbs_notice_center_recordrechargedata::RECHARGE_DATA_EXIST;
            $retCode_Str = 'RECHARGE_DATA_EXIST';
            goto failed;
        }
        // code

        $db = DBPools::default_Db_pools()->dbconnect();
        $data_arr = $rechargeData->toArray();
        $where = [
            RechargeData::DBKey_orderid => $rechargeData->get_orderid()
        ];

        $ret = $db->update(RechargeData::DBKey_tablename, $data_arr, $where, true);

        succ:
        return CommonUtilReturnVar::Ret(true, $retCode, $data, $retCode_Str);
        failed:
        return CommonUtilReturnVar::Ret(false, $retCode, $data, $retCode_Str);
    }

    private function save_rechargedata(RechargeData $rechargeData)
    {
        $db = DBPools::default_Db_pools()->dbconnect();
        $data_arr = $rechargeData->toArray();
        $where = [
            RechargeData::DBKey_orderid => $rechargeData->get_orderid()
        ];

        $ret = $db->update(RechargeData::DBKey_tablename, $data_arr, $where);
    }

    /**
     * 获取充值数据
     * @param string $orderId 订单id
     * @param null $unique_identifier
     * @return RechargeData|null
     */
    public function get_rechargedata($orderId, $unique_identifier = NULL)
    {
        $orderId = strval($orderId);
        $db = DBPools::default_Db_pools()->dbconnect();
        $where = array();
        array_push($where, array(
            RechargeData::DBKey_orderid => $orderId
        ));
        if (!is_null($unique_identifier)) {
            array_push($where, array(
                RechargeData::DBKey_unique_identifier => $unique_identifier
            ));
        }

        $ret = $db->query(RechargeData::DBKey_tablename, array(
            '$or' => $where
        ));

        if (count($ret) == 0) {
            return null;
        }
        $dbRet = $ret [0];
        $data = new RechargeData ();
        $data->fromArray($dbRet);
        return $data;
    }

    /**
     * 校验订单
     *
     * @param string $orderId
     * @return CommonUtilReturnVar
     */
    function check($orderId)
    {
        $retCode = 0;
        $retCode_Str = 'SUCC';
        $data = array();
        // class err_dbs_notice_center_check{}
        $orderId = strval($orderId);
        $rechargeData = $this->get_rechargedata($orderId);
        if (is_null($rechargeData)) {
            $retCode = err_dbs_notice_center_check::RECHARGE_DATA_NOT_EXIST;
            $retCode_Str = 'RECHARGE_DATA_NOT_EXIST:' . $orderId;
            goto failed;
        }

        if ($rechargeData->get_iscompleteverify()) {
            $retCode = err_dbs_notice_center_check::ALREADY_VERIFYED;
            $retCode_Str = 'ALREADY_VERIFYED';
            goto failed;
        }

        $rechargeData->complete_verify();
        $data = $rechargeData->toArray();
        $this->save_rechargedata($rechargeData);

        // code

        succ:
        return CommonUtilReturnVar::Ret(true, $retCode, $data, $retCode_Str);
        failed:
        return CommonUtilReturnVar::Ret(false, $retCode, $data, $retCode_Str);
    }
}