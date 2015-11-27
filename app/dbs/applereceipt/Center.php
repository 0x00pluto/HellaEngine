<?php

namespace app\payverify\dbs\applereceipt;


use app\payverify\dbs\notice\RechargeData;
use hellaEngine\Supports\Common\Util\CommonUtilHttp;
use hellaEngine\Supports\Common\Util\CommonUtilReturnVar;
use hellaEngine\Data\DataBase\DBPools;

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

    // 单例方法,用于访问实例的公共的静态方法
    public static function getInstance()
    {
        if (!(self::$_instance instanceof static)) {
            self::$_instance = new static ();
        }
        return self::$_instance;
    }

    /**
     * 获取所有票据
     *
     * @return CommonUtilReturnVar
     */
    function getall()
    {
        $retCode = 0;
        $retCode_Str = 'SUCC';
        $data = array();
        // class err_dbs_applereceiptcenter_getall{}

        $db = DBPools::default_Db_pools()->dbconnect();

        $ret = $db->query(Data::DBKey_tablename);


        // code

        succ:
        return CommonUtilReturnVar::Ret(true, $retCode, $data, $retCode_Str);
        failed:
        return CommonUtilReturnVar::Ret(false, $retCode, $data, $retCode_Str);
    }

    /**
     * 校验苹果订单
     *
     * @param string $platformid
     *            平台di
     * @param string $orderid
     *            订单id
     * @param string $receipt
     *            票据数据
     * @param integer $rmbnum
     *            金额 分
     * @return CommonUtilReturnVar
     */
    function verify($platformid, $orderid, $receipt, $rmbnum)
    {
        $retCode = 0;
        $retCode_Str = 'SUCC';
        $data = array();

        $platformid = intval($platformid);
        $orderid = strval($orderid);
        $receipt = strval($receipt);
        $rmbnum = intval($rmbnum);
        // class err_dbs_applereceiptcenter_verify{}

        $applereceiptdata = Data::create($platformid, $orderid, $receipt);
        // functionsDump ( $applereceiptdata->toArray () );

        $applereceiptdata->loadfromDB();

        // functionsDump ( $applereceiptdata->is_exist_DBId () );

        if ($applereceiptdata->is_exist_DBId()) {
            $retCode = err_dbs_applereceipt_center_verify::ALREADY_VERIFYED;
            $retCode_Str = 'ALREADY_VERIFYED';
            goto failed;
        }

        // 记录数据
        // $applereceiptdata->mark_dirty();
        $applereceiptdata->saveToDB(null, true);
        // code

        $receipt = json_encode(array(
            "receipt-data" => $receipt
        ));

        $url = "https://sandbox.itunes.apple.com/verifyReceipt";

        $response = CommonUtilHttp::http($url, $receipt, "POST");
        if ($response ['http_code'] != 200) {
            $retCode = err_dbs_applereceipt_center_verify::HTTP_CODE_200;
            $retCode_Str = 'HTTP_CODE_200';
            goto failed;
        }
        $retcodejson = json_decode($response ['response'], true);
        dump($retcodejson);

        if ($retcodejson ["status"] != 0) {
            $retCode = err_dbs_applereceipt_center_verify::VERIFY_STATUS_ERRPR;
            $retCode_Str = 'VERIFY_STATUS_ERRPR';
            goto failed;
        }

        $receiptdata = $retcodejson ['receipt'];

        $rechargedata = new RechargeData ();
        $rechargedata->set_appid('1');
        $rechargedata->set_money($rmbnum);
        $rechargedata->set_platformid($platformid);
        $rechargedata->set_orderid($orderid);
        $rechargedata->set_goodsid($receiptdata ['product_id']);
        $rechargedata->set_goodsnum($receiptdata ['quantity']);
        $rechargedata->set_rechargetime($receiptdata ['original_purchase_date_ms']);
        $rechargedata->set_extinfo($receiptdata);
        $rechargedata->set_unique_identifier($receiptdata ['unique_identifier']);

        Center::getInstance()->recordrechargedata($rechargedata);

        succ:
        return Common_Util_ReturnVar::Ret(true, $retCode, $data, $retCode_Str);
        failed:
        return Common_Util_ReturnVar::Ret(false, $retCode, $data, $retCode_Str);
    }
}