<?php

namespace hellaEngine\Supports\Common\Util;

/**
 * 消息池
 *
 * @author zhipeng
 *
 */
class CommonUtilMessagePool
{
    private $_pool = [];

    /**
     * 压入消息队列
     *
     * @param CommonUtilMessage $message
     */
    public function pushMessage(CommonUtilMessage $message)
    {
        $this->_pool [] = $message;
    }

    /**
     * 弹出消息队列
     *
     * @return null|CommonUtilMessage
     */
    public function popMessage()
    {
        return array_shift($this->_pool);
    }

    /**
     *
     * @var CommonUtilMessagePool
     */
    private static $_defaultMessagePool;

    /**
     * 默认返回消息池
     *
     * @return CommonUtilMessagePool
     */
    public static function defaultMessagePool()
    {
        if (!self::$_defaultMessagePool instanceof self) {
            self::$_defaultMessagePool = new self ();
        }
        return self::$_defaultMessagePool;
    }
}