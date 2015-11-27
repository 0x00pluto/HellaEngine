<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/9
 * Time: 下午8:14
 */

namespace hellaEngine\Data\Attribute;


use hellaEngine\Interfaces\Data\Attribute\AttributeType as AttributeTypeInterface;

/**
 * Class AttributeArray
 * @package hellaEngine\Data\Attribute
 */
class AttributeArray extends AttributeType
{
    /**
     * 获取属性关键字
     * @return mixed
     */
    public function getKeyWord()
    {
        return AttributeTypeInterface::TYPE_ARRAY;
    }


    /**
     * 类型转换
     * @param $value
     * @return mixed
     */
    public function convert($value)
    {
        return $value;
    }
}