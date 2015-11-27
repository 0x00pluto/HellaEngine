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
 * Class AttributeInt
 * @package hellaEngine\Data\Attribute
 */
class AttributeInt extends AttributeType
{
    /**
     * 获取属性关键字
     * @return mixed
     */
    public function getKeyWord()
    {
        return AttributeTypeInterface::TYPE_INT;
    }


    /**
     * 类型转换
     * @param $value
     * @return mixed
     */
    public function convert($value)
    {
        return intval($value);
    }
}