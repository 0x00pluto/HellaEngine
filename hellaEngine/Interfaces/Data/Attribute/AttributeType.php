<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/9
 * Time: 下午8:06
 */

namespace hellaEngine\Interfaces\Data\Attribute;


/**
 * 属性类型
 * Interface AttributeType
 * @package hellaEngine\Interfaces\Data\Attribute
 */
interface AttributeType
{
    /**
     * 整型
     */
    const TYPE_INT = 'int';

    /**
     * 字符串
     */
    const TYPE_STRING = 'string';

    /**
     * bool
     */
    const TYPE_BOOL = 'bool';

    /**
     * 数组
     */
    const TYPE_ARRAY = 'array';

    /**
     * 获取属性关键字
     * @return mixed
     */
    public function getKeyWord();


    /**
     * 类型转换
     * @param $value
     * @return mixed
     */
    public function convert($value);


    /**
     * 匹配
     * @param $value1
     * @param $value2
     * @return boolean
     */
    public function equal($value1, $value2);
}