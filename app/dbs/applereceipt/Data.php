<?php

namespace app\payverify\dbs\applereceipt;

use app\payverify\dbs\DbsBase;

/**
 * 说明
 * 2015年8月6日 下午4:06:12
 *
 * @author zhipeng
 *
 */
class Data extends DbsBase
{

    /**
     * 唯一id
     *
     * @var string
     */
    const DBKey_uuid = "uuid";

    /**
     * 获取 唯一id
     */
    public function get_uuid()
    {
        return $this->getdata(self::DBKey_uuid);
    }

    /**
     * 设置 唯一id
     *
     * @param string $value
     */
    private function set_uuid($value)
    {
        $value = strval($value);
        $this->setdata(self::DBKey_uuid, $value);
    }

    /**
     * 设置 唯一id 默认值
     */
    protected function _set_defaultvalue_uuid()
    {
        $this->set_defaultkeyandvalue(self::DBKey_uuid, '');
    }

    /**
     * 订单id
     *
     * @var string
     */
    const DBKey_orderid = "orderid";

    /**
     * 获取 订单id
     */
    public function get_orderid()
    {
        return $this->getdata(self::DBKey_orderid);
    }

    /**
     * 设置 订单id
     *
     * @param string $value
     */
    public function set_orderid($value)
    {
        $value = strval($value);
        $this->setdata(self::DBKey_orderid, $value);
    }

    /**
     * 渠道id
     *
     * @var string
     */
    const DBKey_platformid = "platformid";

    /**
     * 获取 渠道id
     */
    public function get_platformid()
    {
        return $this->getdata(self::DBKey_platformid);
    }

    /**
     * 设置 渠道id
     *
     * @param string $value
     */
    public function set_platformid($value)
    {
        $value = strval($value);
        $this->setdata(self::DBKey_platformid, $value);
    }

    /**
     * 票据
     *
     * @var string
     */
    const DBKey_receipt = "receipt";

    /**
     * 获取 票据
     */
    public function get_receipt()
    {
        return $this->getdata(self::DBKey_receipt);
    }

    /**
     * 设置 票据
     *
     * @param string $value
     */
    public function set_receipt($value)
    {
        $value = strval($value);
        $this->setdata(self::DBKey_receipt, $value);
    }

    /**
     * 设置 票据 默认值
     */
    protected function _set_defaultvalue_receipt()
    {
        $this->set_defaultkeyandvalue(self::DBKey_receipt, '');
    }

    /**
     * 设置 渠道id 默认值
     */
    protected function _set_defaultvalue_platformid()
    {
        $this->set_defaultkeyandvalue(self::DBKey_platformid, '');
    }

    /**
     * 设置 订单id 默认值
     */
    protected function _set_defaultvalue_orderid()
    {
        $this->set_defaultkeyandvalue(self::DBKey_orderid, '');
    }

    /**
     * 表名
     *
     * @var string
     */
    const DBKey_tablename = "applereceiptdata";

    function __construct()
    {
        parent::__construct(self::DBKey_tablename, array(), array(
            self::DBKey_uuid
        ));
    }

    /**
     * 自动设置uuid
     */
    public function autoset_uuid()
    {
        $this->set_uuid(md5($this->get_receipt()));
    }


    protected function _loadfromDB($db)
    {
        $where = $this->primary_key_query_where();
        $ret = $db->query(self::DBKey_tablename, $where);
        if (count($ret) != 0) {
            $this->fromArray($ret [0]);
        }
    }

    /**
     * create function
     * @param $platformid
     * @param $orderid
     * @param $receipt
     * @return Data
     */
    static function create($platformid, $orderid, $receipt)
    {
        $ins = new self ();
        $ins->set_platformid($platformid);
        $ins->set_orderid($orderid);
        $ins->set_receipt($receipt);
        $ins->autoset_uuid();
        $ins->clear_dirty();

        return $ins;
    }
}