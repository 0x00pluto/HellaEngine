<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/17
 * Time: 下午9:17
 */

namespace hellaEngine\Interfaces\Services;


interface MiddleWare
{
    /**
     *
     * @param array $context
     *            上下文内容
     * @param \Closure $next
     *            下一个函数
     *
     * @return \Closure
     *
     * @example return $next ( $context );
     */
    function handle(array $context, \Closure $next);
}