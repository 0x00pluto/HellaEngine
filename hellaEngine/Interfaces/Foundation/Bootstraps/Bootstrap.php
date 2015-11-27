<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/21
 * Time: 下午2:00
 */

namespace hellaEngine\Interfaces\Foundation\Bootstraps;


use hellaEngine\Application\Application;

/**
 * 启动接口
 * Interface Bootstrap
 * @package hellaEngine\Interfaces\Foundation\Bootstraps
 */
interface Bootstrap
{
    public function bootstrap(Application $app);
}