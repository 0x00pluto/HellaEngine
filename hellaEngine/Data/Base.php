<?php

namespace hellaEngine\Data;

use hellaEngine\Interfaces\Data\Base as BaseInterface;

/**
 * 数据操作基类
 *
 *
 *
 * @author zhipeng
 *
 */
abstract class Base implements BaseInterface
{
    /**
     * 给数据设置默认值
     */
    abstract protected function set_defalutvaluetodata();

    /**
     * 设置单个默认值
     *
     * @param string $key
     * @param
     *            $defalutvalue
     */
    abstract protected function set_defaultkeyandvalue($key, $defalutvalue);

    /**
     * 设置默认值
     *
     * @param array $arr
     */
    abstract protected function set_defaultvalues($arr);

    /**
     * 获取默认值
     *
     * @return array
     */
    abstract protected function get_defaultvalues();

    /**
     * 获取数据
     *
     * @param string $key
     * @return mixed
     *
     */
    abstract protected function getdata($key);

    /**
     * 设置数据
     *
     * @param string $key
     * @param
     *            $value
     * @return boolean 是否设置成功
     */
    abstract protected function setdata($key, $value);

    /**
     * 批量设置数据
     *
     * @param array $dataArr
     * @return boolean 是否设置成功
     */
    abstract protected function setdatas($dataArr);


    /**
     * 反射字段缓存
     * @var array
     */
    protected static $_defaultValueReflection = array();


    /**
     * 通过反射设置默认值
     * @param Base $classObj
     */
    protected static function set_defalutvaluebyreflection(Base $classObj)
    {
        $className = static::class;
        $reflectionArray = null;
        if (isset (self::$_defaultValueReflection [$className])) {
            $reflectionArray = self::$_defaultValueReflection [$className];
            $classObj->set_defaultvalues($reflectionArray);
        } else {
            $reflectionArray = array();
            $methods = get_class_methods($className);
            foreach ($methods as $method_name) {
                if (strpos($method_name, "_set_defaultvalue_") === 0) {
                    $reflectionArray [] = $method_name;
                }
            }
            foreach ($reflectionArray as $method_name) {
                $classObj->$method_name ();
            }

            self::$_defaultValueReflection [$className] = $classObj->get_defaultvalues();
        }

        $classObj->set_defalutvaluetodata();
    }
}