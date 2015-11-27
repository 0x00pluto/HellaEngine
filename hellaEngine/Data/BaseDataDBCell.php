<?php

namespace hellaEngine\Data;

use hellaEngine\Constants\Configure\Config;
use hellaEngine\Data\DataBase\DBMongo;
use hellaEngine\Data\DataBase\DBPools;
use hellaEngine\Exceptions\DataSaveError;
use hellaEngine\Interfaces\Data\BaseDataDBCell as BaseDataDBCellInterface;
use hellaEngine\Supports\Common\Util\CommonUtilArray;


/**
 * 数据操作基类
 *
 * @author zhipeng
 *
 */
abstract class BaseDataDBCell extends BaseDataCell implements BaseDataDBCellInterface
{


    /**
     * 获取数据库表名
     *
     * @return string
     */
    public function get_tablename()
    {
        return $this->table_name;
    }

    /**
     * 数据连接池
     *
     * @return DBPools
     */
    public static function db_pools()
    {
        return DBPools::default_Db_pools();
    }

    /**
     * 数据链接
     *
     * @return DBMongo
     */
    public static function db_connect()
    {
        return self::db_pools()->dbconnect();
    }

    /**
     * 主键字段,支持联合主键
     * @var array
     */
    protected $_primary_key = array();

    /**
     * mongodb主键
     *
     * @var string
     */
    const DBKey_dbid = "_id";
    /**
     *
     * @var null
     */
    private $db_id = null;

    /**
     * 设置mongodb主键
     *
     * @param string $value
     */
    private function set_dbid($value)
    {
        $this->db_id = strval($value);
    }

    /**
     * 获取mongodb主键
     *
     * @return string
     */
    protected function get_dbid()
    {
        return $this->db_id;
    }

    /**
     * 建立索引
     *
     * @param array $indexs
     *            索引 例如{"userid":1}
     * @param bool $unique
     *            是否唯一
     */
    protected function ensureIndex($indexs, $unique = false)
    {
        if (empty ($this->get_tablename())) {
            return;
        }
        $index_name = "index_";
        foreach ($indexs as $key => $value) {
            $index_name .= $key . "_";
        }

        $this->db_ins->ensureIndex($this->get_tablename(), $index_name, $indexs, true, $unique);
    }

    // 数据是否为脏
    private $flag_db_is_dirty = false;

    /**
     * 数据库实例
     *
     * @var DBMongo
     */
    protected $db_ins = NULL;
    /**
     * 数据是否自动加载,
     *
     * @var bool
     */
    private $flag_db_autoload = true;

    /**
     * 是否已经自动加载了
     *
     * @var bool
     */
    private $flag_is_auto_loaded = false;

    /**
     *
     * @param string $tableName
     * @param array $db_field_keys
     *            关键字数组,key=>defalutvalue
     * @param array $db_field_primary_key
     *            主键 [key1,key2]
     * @param bool $auto_save
     *            是否自动保存
     * @param bool $auto_load
     *            是否自动加载,也就是判断isExistDBID 后 ,执行loadfromDB
     */
    function __construct($tableName = self::EMPTY_TABLE_NAME, $db_field_keys = array(), $db_field_primary_key = array(), $auto_save = true, $auto_load = true)
    {
        parent::__construct($db_field_keys);

        // 数据库连接
        $this->db_ins = DBPools::default_Db_pools()->dbconnect();
        $this->table_name = $tableName;

        if (is_array($db_field_primary_key)) {
            $this->set_primary_key($db_field_primary_key);
        }
        // 是否支持自动保存
        if ($auto_save) {
            self::db_pools()->push($this);
        }
        $this->flag_db_autoload = $auto_load;
    }

    /**
     * 数据库主键
     *
     * @return [type] [description]
     */
    private function set_primary_key($arr)
    {
        return $this->_primary_key = $arr;
    }

    /**
     * 加载数据
     *
     * @return boolean
     */
    public final function loadfromDB()
    {
        // 表名为空不读取
        if (empty ($this->table_name)) {
            return false;
        }

        $db = self::db_connect();
        $this->_loadfromDB($db);
        $this->clear_dirty();
        return TRUE;
    }

    /**
     * 加载数据
     *
     * @param DBMongo $db
     */
    abstract protected function _loadfromDB($db);

    /**
     * 保存到数据库
     *
     * @param DBMongo $db
     *            数据库连接
     * @param boolean $force
     *            是否强制保存
     * @return bool 是否真正执行了保存
     */
    public final function saveToDB($db = null, $force = false)
    {
        // 表名为空不保存
        $saved = false;

        // 只读不保存数据
        if ($this->is_readonly()) {
            return $saved;
        }
        if (empty ($this->table_name)) {
            return $saved;
        }
        // 数据已经被删除了,则不保存数据了
        if ($this->get_data_is_delete()) {
            return $saved;
        }
        if ($this->is_dirty() || $force) {

            if (is_null($db)) {
                $db = self::db_connect();
            }
            $saved = $this->_saveToDB($db);
        }

        $this->clear_dirty();
        return $saved;
    }

    /**
     * 数据是否被删除
     *
     * @var bool
     */
    private $flag_data_is_delete = false;

    /**
     * 获取数据是否已经被删除
     *
     * @return boolean
     */
    public function get_data_is_delete()
    {
        return $this->flag_data_is_delete;
    }

    /**
     * 从数据库中删除
     *
     * @return boolean
     */
    public final function removeFromDB()
    {
        // 不能重复执行删除
        if ($this->flag_data_is_delete) {
            return FALSE;
        }
        // 不存在主键不能删除
        if (!$this->is_exist_DBId()) {
            return FALSE;
        }
        $db = self::db_connect();
        $where = array();
        $where [self::DBKey_dbid] = DBMongo::id($this->get_dbid());
        $db->delete($this->get_tablename(), $where);

        $this->flag_data_is_delete = TRUE;
        return TRUE;
    }


    /**
     * 构建主键查询语句
     * @return array
     */
    protected function primary_key_query_where()
    {
        $where = array();
        foreach ($this->_primary_key as $primary_key) {
            if (!empty ($this->getdata($primary_key))) {
                $where [$primary_key] = $this->getdata($primary_key);
            }
        }
        return $where;
    }


    /**
     * 保存数据
     * @param $db
     * @return mixed
     * @throws DataSaveError
     */
    protected function _saveToDB(DBMongo $db)
    {

        // 设置用户主键
        $where = $this->primary_key_query_where();
        // 数据是否存在
        $dataExists = false;
        // 设置mongo主键
        $_id = DBMongo::id();

        if (!empty ($this->get_dbid())) {
            $dataExists = true;
            $_id = DBMongo::id($this->get_dbid());
        } else {
        }

        $where [self::DBKey_dbid] = $_id;
        if ($dataExists) {
            $or = [
                [
                    self::DBKey_update_at => [
                        '$exists' => false
                    ]
                ],
                [
                    self::DBKey_update_at => $this->get_update_at()
                ]
            ];
            $where ['$or'] = $or;
        }

        $this->updateTimestamps();

        $savedatas = $this->_data_contains;

        if ($dataExists) {
            $ret = $db->update($this->get_tablename(), $savedatas, $where, false);
        } else {
            $savedatas [self::DBKey_dbid] = $_id;
            $ret = $db->insert($this->get_tablename(), $savedatas, false);
        }

        if (!$ret) {
            $exception = new DataSaveError ('save to db failed!' . 'tablename:' . $this->get_tablename());
            throw $exception;
        }
        return $ret;
        // functionsDump ( $ret );
    }

    /**
     * 本次数据的脏字段
     *
     * @var array
     */
    private $dirty_keys = array();


    /**
     * 标记脏的字段
     * @param string $key
     * @param mixed $oldValue
     * @param mixed $newValue
     */
    private function set_dirty_key($key, $oldValue, $newValue)
    {
        $this->dirty_keys [$key] = array(
            'oldvalue' => $oldValue,
            'newvalue' => $newValue
        );
    }

    private function clear_dirty_key()
    {
        $this->dirty_keys = array();
    }

    /**
     * 获取导致脏数据的字段
     *
     * @return array
     */
    public function get_dirty_key()
    {
        return $this->dirty_keys;
    }

    /**
     * 标记数据脏
     *
     * @param string $key
     *            脏的字段
     * @param string $oldvalue
     *            原始数据
     * @param string $newvalue
     *            新数据
     */
    public function mark_dirty($key = NULL, $oldvalue = NULL, $newvalue = NULL)
    {
        // dump_stack ();
        if (!is_null($key)) {
            $this->set_dirty_key($key, $oldvalue, $newvalue);
        }
        $this->flag_db_is_dirty = true;
    }

    /**
     * 是否有脏数据
     *
     * @return boolean
     */
    public function is_dirty()
    {
        return $this->flag_db_is_dirty;
    }

    /**
     * 清除脏数据标志位
     */
    protected function clear_dirty()
    {
        $this->flag_db_is_dirty = false;
        $this->clear_dirty_key();
    }


    /**
     * 设置数据
     * @param string $key
     * @param $value
     * @return bool
     */
    protected function setdata($key, $value)
    {
        return $this->D_setter($key, $value);
    }

    /**
     * 数据设置器
     * 会自动标记脏数据
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    protected function D_setter($key, $value)
    {
        $key = strval($key);

        // mongodb主键
        if ($key == self::DBKey_dbid) {
            $this->set_dbid($value);
            return true;
        }

        // functionsDump ( $this->is_exist_DBId () );
        // dump_stack ();
        // functionsDump ( $this->_loading_data );

        // assert ( false, 0 );
        if (!$this->_loading_data) {
            // 不是从db加载数据

            // 只读数据
            if ($this->is_readonly()) {

                // 实际上问题不大.因为在使用只读数据的时候,也可以参与运算.但是不会赋值
                // 在需要真实数据的时候,应该还会使用用户主体数据的
                // CommonUtilLog::record ( CommonUtilLog::ERROR, 'set_readonly_db', [
                // 'tablename' => $this->get_tablename (),
                // 'dbkey' => $key,
                // 'oldvalue' => $this->getdata ( $key ),
                // 'newvalue' => $value,
                // 'stack' => dump_stack ( true )
                // ] );
            }

            if (C(Config::DEBUG)) {
                $checkret = DBMongo::check_data_error($value);
                if ($checkret != DBMongo::CHECK_DATA_RETCODE_SUCC) {
                    dump("errorset:" . $key);
                    dump_stack();
                    dump($value, true);
                    return false;
                }
            }

            $oldValue = $this->getdata($key);
            if (!is_array($value) && $oldValue === $value) {
                return true;
            }

            if (C(Config::DEBUG_DB)) {
                $this->mark_dirty($key, $oldValue, $value);
            } else {
                $this->mark_dirty();
            }
        }
        $this->_data_contains [$key] = $value;

        return TRUE;
    }

    /**
     * 是否正在从数据库加载数据
     *
     * @var bool
     */
    private $_loading_data = FALSE;

    /*
     * (non-PHPdoc)
     * @see \dbs\base\dbs_base_operate::fromArray()
     */
    function fromArray($arr, $exclude = NULL)
    {
        if (empty ($arr)) {
            return false;
        }
        $this->_loading_data = TRUE;
        $this->set_defalutvaluetodata();
        $this->setdatas($arr);
        $this->clear_dirty();
        $this->_loading_data = FALSE;
        return true;
    }

    /**
     * db 中是否有数据
     *
     * @return boolean
     */
    function db_has_data()
    {
        if (empty ($this->get_dbid())) {
            return false;
        }
        return true;
    }

    /**
     * DBID 是否存在,可以用来判断是否有数据
     *
     * @return boolean [description]
     */
    function is_exist_DBId()
    {
        if (empty ($this->get_dbid())) {
            return false;
        }
        return true;
    }

    /**
     * dumpDB....
     */
    function dumpDB()
    {
        dump($this->_data_contains, false, 1);
    }

    /**
     * 是否支持自动加载
     *
     * @return boolean
     */
    function isSupportAutoload()
    {
        return $this->flag_db_autoload;
    }

    /**
     * 自动加载数据库,
     *
     * @return boolean TRUE 本次进行了实际的db加载,FALSE 本次没有进行DB操作
     *
     */
    function autoloadFromDB()
    {
        if ($this->isSupportAutoload() && !$this->flag_is_auto_loaded) {
            $this->loadfromDB();
            $this->flag_is_auto_loaded = true;

            return TRUE;
        }
        return FALSE;
    }

    /**
     * 数据库快照
     *
     * @var array
     */
    private $_db_snapshot = array();

    /**
     * 数据库快照
     *
     * @param string $flag
     */
    function db_snapshot($flag = null)
    {
        $flag = strval($flag);
        $this->_db_snapshot [$flag] = CommonUtilArray::clone_array($this->_data_contains);
    }

    /**
     * 从快照恢复
     *
     * @param string $flag
     */
    function db_restorefromsnapshot($flag = null)
    {
        $flag = strval($flag);

        if (!isset ($this->_db_snapshot [$flag])) {
            return false;
        }

        // 记录老数据
        $old = $this->_data_contains;

        // 恢复原始数据
        $this->_data_contains = $this->_db_snapshot [$flag];
        // 删除快照
        unset ($this->_db_snapshot [$flag]);

        $this->clear_dirty_key();
        $this->mark_dirty('db_restorefromsnapshot', $old, $this->_data_contains);
        return true;
    }

    /**
     * 是否只读,如果
     *
     * @var bool
     */
    private $readonly = FALSE;

    /**
     * 是否只读,也就是所有的数据都不能写
     *
     * @return boolean
     */
    public function is_readonly()
    {
        return $this->readonly;
    }

    public function set_readonly($value)
    {
        $this->readonly = boolval($value);
    }

    /**
     * create_at
     *
     * @var string
     */
    const DBKey_create_at = "create_at";

    /**
     * 获取 create_at
     */
    public function get_create_at()
    {
        return $this->getdata(self::DBKey_create_at);
    }

    /**
     * 设置 create_at
     *
     * @param int $value
     */
    protected function set_create_at($value)
    {
        $value = intval($value);
        $this->setdata(self::DBKey_create_at, $value);
    }

    /**
     * 设置 create_at 默认值
     */
    protected function _set_defaultvalue_create_at()
    {
        $this->set_defaultkeyandvalue(self::DBKey_create_at, time());
    }

    private function updateTimestamps()
    {
        $this->set_update_at(time());
    }

    /**
     * update_at
     *
     * @var string
     */
    const DBKey_update_at = "update_at";

    /**
     * 获取 update_at
     */
    public function get_update_at()
    {
        return $this->getdata(self::DBKey_update_at);
    }

    /**
     * 设置 update_at
     *
     * @param int $value
     */
    protected function set_update_at($value)
    {
        $value = intval($value);
        $this->setdata(self::DBKey_update_at, $value);
    }

    /**
     * 设置 update_at 默认值
     */
    protected function _set_defaultvalue_update_at()
    {
        $this->set_defaultkeyandvalue(self::DBKey_update_at, time());
    }


    /**
     * 获取所有数据
     * @param array $where
     * @return array:static
     */
    public static function all(array $where = [])
    {
        $result = [];
        $db = static::db_connect();

        $ins = new static ();
        $dbResults = $db->query($ins->get_tablename(), $where);

        foreach ($dbResults as $dbResult) {
            $dbIns = new static ();
            $dbIns->fromArray($dbResult);
            $result [] = $dbIns;

        }
        return $result;
    }
}
