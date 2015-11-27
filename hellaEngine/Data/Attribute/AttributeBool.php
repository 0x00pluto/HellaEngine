<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/9
 * Time: 下午8:18
 */

namespace hellaEngine\Data\Attribute;


use hellaEngine\Interfaces\Data\Attribute\AttributeType as AttributeInterface;

/**
 * Class AttributeBool
 * @package hellaEngine\Data\Attribute
 */
class AttributeBool extends AttributeType
{
    /**
     * 类型转换
     * @param $value
     * @return mixed
     */
    public function convert($value)
    {
        return boolval($value);
    }

    /**
     * 获取属性关键字
     * @return mixed
     */
    public function getKeyWord()
    {
        return AttributeInterface::TYPE_BOOL;
    }


}