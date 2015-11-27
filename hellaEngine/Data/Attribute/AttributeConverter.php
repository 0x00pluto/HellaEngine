<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/9
 * Time: 下午8:22
 */

namespace hellaEngine\Data\Attribute;


use hellaEngine\Exceptions\Data\Attribute\AttributeNotFound;
use hellaEngine\Interfaces\Data\Attribute\AttributeType;

/**
 * Class AttributeConverter
 * @package hellaEngine\Data\Attribute
 */
class AttributeConverter
{
    private $converterTypes = [];

    private static $ins = null;

    /**
     * AttributeConverter constructor.
     */
    private function __construct()
    {
        $this->bootstrap();
    }

    /**
     * 默认转换器
     * @return AttributeConverter|null
     */
    public static function DefaultConverter()
    {
        if (!self::$ins instanceof self) {
            self::$ins = new self();
        }
        return self::$ins;
    }


    /**
     * 初始化函数
     */
    public function bootstrap()
    {
        $this->addAttributeType(new AttributeInt());
        $this->addAttributeType(new AttributeBool());
        $this->addAttributeType(new AttributeString());
        $this->addAttributeType(new AttributeArray());
    }

    /**
     * 添加属性类型
     * @param AttributeType $newType
     */
    public function addAttributeType(AttributeType $newType)
    {
        $this->converterTypes[$newType->getKeyWord()] = $newType;
    }

    /**
     * 获取属性
     * @param $keyWord
     * @return mixed
     */
    private function getAttributeType($keyWord)
    {
        if (isset($this->converterTypes[$keyWord])) {
            return $this->converterTypes[$keyWord];
        }
        throw new AttributeNotFound($keyWord);
    }

    public function convert($typeKeyWord, $value)
    {

    }

    public function equal($typeKeyWord, $value1, $value2)
    {

    }


}