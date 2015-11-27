<?php
use \Illuminate\Container\Container;

/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/20
 * Time: 下午2:57
 */

if (!function_exists("app")) {
    /**
     * @param null $make
     * @param array $parameters
     * @return \hellaEngine\Application\Application
     */
    function app($make = null, $parameters = [])
    {
        if (is_null($make)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($make, $parameters);
    }
}
if (!function_exists("config")) {
    /**
     * 获取配置服务
     *
     * @param null $configure 如果为null 返回所有设置,可以设置 例如 'app' 则调用configure
     * 下的 app.php
     * @return \hellaEngine\Application\Application
     */
    function config($configure = null)
    {
        if (is_null($configure)) {
            return app('config');
        }
        return app('config')[$configure];
    }
}
if (!function_exists("route")) {
    /**
     * 接口路由器
     * @return hellaEngine\Foundation\Route\ServiceRouteCollection
     */
    function route()
    {
        return app('serviceRoute');
    }
}

if (!function_exists("eventDispatcher")) {
    /**
     *
     * @return hellaEngine\Events\EventDispatcher
     */
    function eventDispatcher()
    {
        return app("events");
    }
}

if (!function_exists("C")) {
    /**
     * 设置或者获取全局变量
     * @param string $name 全局变量的名称
     * @param string $value 如果为默认值,则为获取操作
     * @param string $default 默认值
     * @return array|null
     */
    function C($name = null, $value = null, $default = null)
    {
        // 无参数时获取所有
        $config = config();
        if (empty ($name)) {
            return $config->all();
        }
        // 优先执行设置获取或赋值
        if (is_null($value)) {
            return $config->get($name, $default);
        } else {
            $config->set($name, $value);
            return $value;
        }
    }
}


if (!function_exists('array_key_exists_faster')) {
    function array_key_exists_faster($key, $array)
    {
        return (isset ($arr [$key]) || array_key_exists($key, $array));
    }
}

if (!function_exists('dump')) {

    function dump_pool_start()
    {
        \hellaEngine\Supports\utils\functions\functionDump::getInstance()->dump_pool_start();
    }

    function dump_pool_end()
    {
        \hellaEngine\Supports\utils\functions\functionDump::getInstance()->dump_pool_end();
    }

    function dump_pool($varVal, $isExit = FALSE, $linestack = 0)
    {
        \hellaEngine\Supports\utils\functions\functionDump::getInstance()->dump_pool($varVal, $isExit, $linestack + 1);
    }


    /**
     * @param $varVal
     * @param bool|FALSE $isExit
     * @param int $linestack 输出行信息 一般默认即可
     */
    function dump($varVal, $isExit = FALSE, $linestack = 0)
    {
        \hellaEngine\Supports\utils\functions\functionDump::getInstance()->dump($varVal, $isExit, $linestack + 1);
    }

    /**
     * 打印调用堆栈
     */
    function dump_stack($return = FALSE)
    {
        return \hellaEngine\Supports\utils\functions\functionDump::getInstance()->dump_stack(1, $return);
    }
}
