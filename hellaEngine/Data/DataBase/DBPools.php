<?php

namespace hellaEngine\Data\DataBase;
;

use hellaEngine\Configure\Constants;
use hellaEngine\Constants\Configure\Config;
use hellaEngine\Data\BaseDataDBCell;
use hellaEngine\Supports\Common\Util\CommonUtilArray;

/**
 * db数据池
 *
 * @author zhipeng
 *
 */
class DBPools
{
    /**
     * 数据库连接
     *
     * @var DBMongo
     */
    private $_db_connection;

    /**
     * 活动数据池
     *
     * @var array
     */
    private $_db_actives = [];

    /**
     * 启动数据池
     */
    public function begin()
    {
        $this->_db_actives = array();
    }

    public function end()
    {
        // empty
    }

    private function __construct()
    {
    }

    /**
     * create function
     *
     * @param DBMongo $dbConnection
     * @return DBPools
     */
    public static function create(DBMongo $dbConnection)
    {
        $ins = new self ();
        $ins->_db_connection = $dbConnection;
        return $ins;
    }

    /**
     * 增加需要操作的DB实例
     *
     * @param BaseDataDBCell $dbs
     */
    public function push($dbs)
    {
        foreach ($this->_db_actives as $value) {
            if ($value === $dbs) {
                return;
            }
        }
        $this->_db_actives [] = $dbs;
    }

    /**
     * 保存数据池
     */
    public function save()
    {
        $db = $this->_db_connection;
        $dbs_arr = $this->_db_actives;
        $debug_db = C(Constants::DEBUG_DB, null, false);
        $debug_arr = [];
        if ($debug_db) {
            $debug_arr = CommonUtilArray::getvalue($GLOBALS, Config::DEBUG_DB_DIRTY_KEY, array())->value();
        }
        foreach ($dbs_arr as $value) {
            if (!$value instanceof BaseDataDBCell) {
                continue;
            }
            if ($debug_db && $value->is_dirty()) {
                $dbinfo = array(
                    'classname' => get_class($value),
                    'info' => $value->get_dirty_key()
                );
            }
            $bsave = $value->saveToDB($db);
            if ($debug_db && $bsave) {
                array_push($debug_arr, $dbinfo);
            }
        }
        if ($debug_db && !empty($debug_arr)) {
            $GLOBALS [Config::DEBUG_DB_DIRTY_KEY] = $debug_arr;
        }
        $this->_db_actives = [];
    }

    /**
     * 获取数据连接
     *
     * @return DBMongo
     */
    public function dbconnect()
    {
        return $this->_db_connection;
    }

    /**
     * 默认数据池
     *
     * @var DBPools
     */
    private static $_default_instance;

    /**
     * 默认数据池
     *
     * @return DBPools
     */
    public static function default_Db_pools()
    {
        if (!(self::$_default_instance instanceof self)) {
            $db_ins = new DBMongo (C(Constants::Const_DB_Connection));
            $db_ins->selectDB(C(Constants::Const_DB_Name));

            self::$_default_instance = self::create($db_ins);
        }
        return self::$_default_instance;
    }
}