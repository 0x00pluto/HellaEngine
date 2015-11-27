<?php

namespace hellaEngine\Services;

use hellaEngine\data\BaseDataCell;

/**
 * 说明
 * 2015年8月3日 下午4:09:59
 *
 * @author zhipeng
 *
 */
class BaseCallableFunctionData extends BaseDataCell
{
    /**
     * 是否是调试方法
     *
     * @var string
     */
    const DBKey_isDebugFunction = "isDebugFunction";

    /**
     * 获取 是否是调试方法
     */
    public function get_isDebugFunction()
    {
        return $this->getdata(self::DBKey_isDebugFunction);
    }

    /**
     * 设置 是否是调试方法
     *
     * @param bool $value
     */
    public function set_isDebugFunction($value)
    {
        $value = boolval($value);
        $this->setdata(self::DBKey_isDebugFunction, $value);
    }

    /**
     * 设置 是否是调试方法 默认值
     */
    protected function _set_defaultvalue_isDebugFunction()
    {
        $this->set_defaultkeyandvalue(self::DBKey_isDebugFunction, false);
    }

    /**
     * 函数名称
     *
     * @var string
     */
    const DBKey_functionname = "functionname";

    /**
     * 获取 函数名称
     */
    public function get_functionname()
    {
        return $this->getdata(self::DBKey_functionname);
    }

    /**
     * 设置 函数名称
     *
     * @param string $value
     */
    public function set_functionname($value)
    {
        $value = strval($value);
        $this->setdata(self::DBKey_functionname, $value);
    }

    /**
     * 设置 函数名称 默认值
     */
    protected function _set_defaultvalue_functionname()
    {
        $this->set_defaultkeyandvalue(self::DBKey_functionname, "");
    }

    /**
     * cachetime
     *
     * @var string
     */
    const DBKey_cachetime = "cachetime";

    /**
     * 获取 cachetime
     */
    public function get_cachetime()
    {
        return $this->getdata(self::DBKey_cachetime);
    }

    /**
     * 设置 cachetime
     *
     * @param unknown $value
     */
    public function set_cachetime($value)
    {
        $value = intval($value);
        $this->setdata(self::DBKey_cachetime, $value);
    }

    /**
     * 设置 cachetime 默认值
     */
    protected function _set_defaultvalue_cachetime()
    {
        $this->set_defaultkeyandvalue(self::DBKey_cachetime, 0);
    }
}