<?php

namespace hellaEngine\Supports\Common\Util;

use hellaEngine\Configure\Constants;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
 *
 * @package common
 * @subpackage util
 * @author kain
 *
 */

/**
 * 简单日志工具
 */
class CommonUtilLog
{

    /**
     * Detailed debug information
     */
    const DEBUG = Logger::DEBUG;

    /**
     * Interesting events
     *
     * Examples: User logs in, SQL logs.
     */
    const INFO = Logger::INFO;

    /**
     * Uncommon events
     */
    const NOTICE = Logger::NOTICE;

    /**
     * Exceptional occurrences that are not errors
     *
     * Examples: Use of deprecated APIs, poor use of an API,
     * undesirable things that are not necessarily wrong.
     */
    const WARNING = Logger::WARNING;

    /**
     * Runtime errors
     */
    const ERROR = Logger::ERROR;

    /**
     * Critical conditions
     *
     * Example: Application component unavailable, unexpected exception.
     */
    const CRITICAL = Logger::CRITICAL;

    /**
     * Action must be taken immediately
     *
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    const ALERT = Logger::ALERT;

    /**
     * Urgent alert.
     */
    const EMERGENCY = Logger::EMERGENCY;

    /**
     *
     * @var \Monolog\Logger
     */
    private static $_logger;

    /**
     *
     * @return \Monolog\Logger
     */
    public static function getLogger()
    {
        if (is_null(self::$_logger)) {
            self::$_logger = new Logger ('debug_logger');
//            dump(C("app." . Constants::LOG_PATH));
            $logPath = config()['app'][Constants::LOG_PATH] . DIRECTORY_SEPARATOR;
//            dump($logPath);
            self::$_logger->pushHandler(new StreamHandler ($logPath . 'game_debug.log', Logger::DEBUG));
            self::$_logger->pushHandler(new StreamHandler ($logPath . 'game_info.log', Logger::INFO));
            self::$_logger->pushHandler(new StreamHandler ($logPath . 'game_error.log', Logger::ERROR));

            self::$_logger->pushHandler(new FirePHPHandler ());
        }

        return self::$_logger;
    }

    /**
     * 记录日志
     *
     * @param int $level
     * @param string $recordtype
     * @param mixed $context
     *            可以为数组 或者任意内容
     */
    public static function record($level, $recordtype, $context = [])
    {
        $recodecontext = [];
        if (is_array($context)) {
            $recodecontext = $context;
        } elseif (is_object($context)) {
            $recodecontext = [
                var_export($context, true)
            ];
        } else {
            $recodecontext = [
                $context
            ];
        }

        if (true) {
            $debuginfo = debug_backtrace();
            $debuginfo = $debuginfo [1];
            $lineinfo = $debuginfo ["file"] . ":" . $debuginfo ['line'];
            $recodecontext ['lineinfo'] = $lineinfo;
        }

        self::getLogger()->addRecord($level, $recordtype, $recodecontext);

        // 如果在调试中,则显示出来
        // if (C ( \configure_constants::DUMP_ENABLE )) {
        // functionsDump ( [
        // 'message' => $recordtype,
        // 'context' => $context
        // ], false, 1 );
        // }
    }

    /**
     *
     * @see Common_Util_Log::record
     */
    public static function record_error($recordtype, $context = [])
    {
        self::record(self::ERROR, $recordtype, $context);
    }
}

?>