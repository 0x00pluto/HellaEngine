<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/22
 * Time: 下午12:01
 */

namespace hellaEngine\Interfaces\Data;


interface BaseDataDBCell extends BaseDataCell
{
    /**
     *  空表名
     * @var string
     */
    const EMPTY_TABLE_NAME = "";
}