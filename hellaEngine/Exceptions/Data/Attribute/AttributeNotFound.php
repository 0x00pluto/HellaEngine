<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/9
 * Time: 下午8:27
 */

namespace hellaEngine\Exceptions\Data\Attribute;


/**
 * Class AttributeNotFound
 * @package hellaEngine\Exceptions\Data\Attribute
 */
class AttributeNotFound extends \LogicException
{


    /**
     * @param string $TypeName 类型名称
     */
    public function __construct($TypeName)
    {
        parent::__construct("$TypeName not found");
    }
}