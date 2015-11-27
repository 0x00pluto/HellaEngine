<?php

namespace hellaEngine\Data;

/**
 * 数据操作基本单元
 *
 * @author zhipeng
 *
 */
abstract class BaseDataCell extends Base implements \hellaEngine\Interfaces\Data\BaseDataCell
{
    /**
     *
     * @param array $defaultvalue
     *            默认值
     */
    function __construct($defaultvalue = array())
    {
        $this->set_defaultvalues($defaultvalue);
        self::set_defalutvaluebyreflection($this);
        $this->set_defalutvaluetodata();
    }

    /**
     * 数据容器
     *
     * @var array
     */
    protected $_data_contains = [];

    /**
     * 默认值
     *
     * @var array
     */
    protected $_defaultvalue = [];

    /**
     * 获取数据
     * @param string $key
     * @return mixed
     */
    protected function getdata($key)
    {
        $key = strval($key);
        if (isset ($this->_data_contains [$key])) {
            return $this->_data_contains [$key];
        }
        if (isset ($this->_defaultvalue [$key])) {
            return $this->_defaultvalue [$key];
        }
        return null;
    }


    /**
     * @param string $key
     * @param $value
     * @return bool
     */
    protected function setdata($key, $value)
    {
        $key = strval($key);
        if (array_key_exists_faster($key, $this->_defaultvalue)) {
            $this->_data_contains [$key] = $value;
            return true;
        }
        return false;
    }


    /**
     * @param array $dataArr
     * @return bool
     */
    protected function setdatas($dataArr)
    {
        if (empty ($dataArr) || !is_array($dataArr)) {
            return false;
        }
        foreach ($dataArr as $key => $value) {
            $this->setdata($key, $value);
        }
        return TRUE;
    }

    /*
     * (non-PHPdoc)
     * @see \dbs\base\dbs_base_operate::set_defaultvalues()
     */
    protected function set_defaultvalues($arr)
    {
        if (!is_array($arr)) {
            return;
        }
        $this->_defaultvalue = array_merge($this->_defaultvalue, $arr);
    }

    /*
     * (non-PHPdoc)
     * @see \dbs\base\dbs_base_operate::get_defaultvalues()
     */
    protected function get_defaultvalues()
    {
        return $this->_defaultvalue;
    }

    /**
     * 设置单个默认值
     *
     * @param string $key
     * @param mixed $defalutvalue
     */
    protected function set_defaultkeyandvalue($key, $defalutvalue)
    {
        $key = strval($key);
        $this->_defaultvalue [$key] = $defalutvalue;
    }

    /*
     * (non-PHPdoc)
     * @see \dbs\base\dbs_base_operate::set_defalutvaluetodata()
     */
    protected function set_defalutvaluetodata()
    {
        $this->_data_contains = $this->_defaultvalue;
    }


    /**
     * 从数组导入数据
     * @param array $arr
     * @param null $exclude
     * @return bool
     */
    public function fromArray($arr, $exclude = NULL)
    {
        if (empty ($arr)) {
            return false;
        }
        $this->set_defalutvaluetodata();

        if (empty ($exclude)) {
            $this->setdatas($arr);
        } else {
            foreach ($arr as $key => $value) {
                if (isset ($exclude [$key])) {
                    continue;
                }
                $this->setdata($key, $value);
            }
        }
        return TRUE;
    }


    /**
     * 导出成数组
     * @param null $filter
     * @param null $excludeFilter
     * @return array
     */
    public function toArray($filter = NULL, $excludeFilter = NULL)
    {
        $arr = array();
        if (empty ($filter)) {
            $arr = $this->_data_contains;
        } else {
            foreach ($filter as $key) {
                if (array_key_exists_faster($key, $this->_data_contains)) {
                    $arr [$key] = $this->_data_contains [$key];
                }
            }
        }

        if (!empty ($excludeFilter)) {
            foreach ($excludeFilter as $key) {
                unset ($arr [$key]);
            }
        }
        return $arr;
    }


    /**
     * @param array $arr
     * @return static
     */
    public static function create_with_array(array $arr)
    {
        $ins = new static ();
        $ins->fromArray($arr);
        return $ins;
    }
}