<?php

namespace hellaEngine\Supports\utils\functions;

use hellaEngine\Configure\Constants;
use utilphp\util;

class functionDump
{
    /**
     * 富文本输出
     *
     * @var unknown
     */
    const RICH_HTML = TRUE;
    /**
     * singleton
     */
    private static $_instance;

    private function __construct()
    {
        // echo 'This is a Constructed method;';
    }

    public function __clone()
    {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }

    // 单例方法,用于访问实例的公共的静态方法
    public static function getInstance()
    {
        if (!(self::$_instance instanceof static)) {
            self::$_instance = new static ();
        }
        return self::$_instance;
    }

    function dump($varVal, $isExit = FALSE, $linestack = 0)
    {
        if (config('app')[Constants::DEBUG]) {
            $debuginfo = debug_backtrace();
            $debuginfo = $debuginfo [$linestack];
            echo $debuginfo ["file"] . ":" . $debuginfo ['line'] . '<br>';
            if (self::RICH_HTML) {
                util::var_dump($varVal, false, -1);
            } else {

                ob_start();
                var_dump($varVal);
                $varVal = ob_get_clean();
                $varVal = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $varVal);
                echo '<pre>' . $varVal . '</pre>';
            }

            $isExit && exit ();
        }
    }

    /**
     * 打印调用堆栈
     */
    function dump_stack($linestack = 0, $return = FALSE)
    {
        $debuginfo = debug_backtrace();
        $stack = array();
        foreach ($debuginfo as $value) {
            $info = $value ['file'] . ':' . $value ['line'] . " " . $value ['function'];
            array_push($stack, $info);
        }
        array_shift($stack);
        if ($return) {
            return $stack;
        } else {
            $this->dump($stack, FALSE, $linestack + 1);
        }
    }

    /**
     * 管道输出标志位
     *
     * @var unknown
     */
    private $dump_pool_enable = FALSE;

    /**
     * 开始管道输出
     */
    public function dump_pool_start()
    {
        $this->dump_pool_enable = TRUE;
    }

    /**
     * 结束管道输出
     */
    public function dump_pool_end()
    {
        $this->dump_pool_enable = FALSE;
    }

    /**
     * 管道输出
     *
     * @param mixed $varVal
     *            输出的变量
     * @param bool $isExit
     *            是否中断
     * @param number $linestack
     *            堆栈数量
     */
    function dump_pool($varVal, $isExit = FALSE, $linestack = 0)
    {
        if ($this->dump_pool_enable) {
            $this->dump($varVal, $isExit, $linestack + 1);
        }
    }
}


