<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/20
 * Time: 下午8:09
 */

namespace hellaEngine\Supports\Common\Util;


class HttpCache
{
    /**
     * @return string
     */
    static function getCachePath()
    {
        return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'Caches' . DIRECTORY_SEPARATOR;
    }
}