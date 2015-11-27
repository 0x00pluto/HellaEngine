<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/21
 * Time: 下午2:05
 */

namespace hellaEngine\Interfaces\Application\Processes;


use hellaEngine\Application\Processes\ProcessData;
use hellaEngine\Services\Base;

/**
 * 命令过滤类,主要用于是否执行Service,例如需要登录调用的接口
 * Interface ProcessFilter
 * @package hellaEngine\interfaces\Application\Processes
 */
interface ProcessFilter
{
    /**
     * 命令过滤函数
     * @param Base $classIns
     * @param ProcessData $processData
     * @return bool True为执行,False为不执行
     */
    function filter(Base $classIns, ProcessData $processData);
}