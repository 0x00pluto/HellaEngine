<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/9
 * Time: 下午8:12
 */

namespace hellaEngine\Data\Attribute;


/**
 * Class AttributeType
 * @package hellaEngine\Data\Attribute
 */
abstract class AttributeType implements \hellaEngine\Interfaces\Data\Attribute\AttributeType
{


    /**
     * 匹配
     * @param $value1
     * @param $value2
     * @return boolean
     */
    public function equal($value1, $value2)
    {
        return $this->convert($value1) === $this->convert($value2);
    }


}