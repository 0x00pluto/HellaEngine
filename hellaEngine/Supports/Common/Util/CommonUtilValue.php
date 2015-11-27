<?php

namespace hellaEngine\Supports\Common\Util;

/**
 * 装箱的方法
 *
 * @author zhipeng
 *
 */
class CommonUtilValue
{
    private $_value;

    /**
     * 构造方法
     *
     * @param mixed $value
     * @return CommonUtilValue
     */
    static function build($value)
    {
        return new self ($value);
    }

    function __construct($value)
    {
        $this->_value = $value;
    }

    function int_value()
    {
        return intval($this->_value);
    }

    function double_value()
    {
        return doubleval($this->_value);
    }

    function float_value()
    {
        return floatval($this->_value);
    }

    function string_value()
    {
        return strval($this->_value);
    }

    function bool_value()
    {
        return boolval($this->_value);
    }

    /**
     * 原始数据
     */
    function value()
    {
        return $this->_value;
    }

    function is_null()
    {
        return is_null($this->_value);
    }

    function __clone()
    {
        dump("__clone");
    }
}