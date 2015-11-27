<?php

namespace hellaEngine\Services;

use hellaEngine\Application\Application;
use hellaEngine\Interfaces\Services\MiddleWare;
use hellaEngine\Supports\Common\Util\CommonUtilFunctions;
use hellaEngine\Supports\Common\Util\CommonUtilReturnVar;
use hellaEngine\Configure\Constants;

/**
 * 服务基类
 *
 * @author zhipeng
 *
 */
abstract class Base
{

    /**
     * @var string
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


    /**
     * 服务借口列表
     *
     * @var array
     */
    protected $service_list = [];

    /**
     * 开启服务
     *
     * @param array $functionNames
     *            可用的服务名
     * @param bool $isDebug
     * @return null
     */
    protected function services_enable(array $functionNames, $isDebug = FALSE)
    {
        if (empty ($functionNames)) {
            return;
        }
        foreach ($functionNames as $value) {
            $this->service_enable($value, $isDebug);
        }
    }


    /**
     * 添加可用接口
     *
     * @param string $functionName
     * @param bool $isDebug
     * @return bool
     */
    protected function service_enable($functionName, $isDebug = FALSE)
    {
        if ($this->is_services_enable($functionName)) {
            return true;
        }

        $data = new BaseCallableFunctionData ();
        $data->set_functionname($functionName);
        $data->set_isDebugFunction($isDebug);

        // 发行版测试函数不让调用
        if (!C(Constants::DEBUG, null, false) && $isDebug) {
            return true;
        }
        $this->service_list [$data->get_functionname()] = $data->toArray();
        return true;
    }

    /**
     * 服务是否可用
     *
     * @param string $functionName
     *            服务器名称,函数名
     * @return boolean 是否可用
     */
    public function is_services_enable($functionName)
    {
        if (empty ($functionName)) {
            return false;
        }
        return isset ($this->service_list [$functionName]);
    }

    /**
     * 调用服务
     *
     * @param string $functionName
     *            服务名称
     * @param array $params
     * @return mixed
     */
    public function call_service($functionName, $params = [])
    {
        if (!$this->is_services_enable($functionName)) {
            return false;
        }

        $callFunction = $this->createMiddleFunction();
        $context = [
            'functionName' => $functionName,
            'params' => $params
        ];
        $callReturn = $callFunction ($context);
        return $callReturn;

    }

    /**
     * 创建中间层链式闭包回调
     *
     * @return \Closure
     */
    private function createMiddleFunction()
    {
        if (empty ($this->middleWares)) {
            return function ($context) {
                $callReturn = CommonUtilFunctions::call_class_func_named_object_array(
                    static::class,
                    $context ['functionName'],
                    $this,
                    $context ['params']);
                return $callReturn;
            };
        } else {
            return function ($context) {
                $middleWare = array_shift($this->middleWares);
                if ($middleWare instanceof MiddleWare) {
                    return $middleWare->handle($context, $this->createMiddleFunction());
                }
            };
        }
    }

    /**
     *
     * @var array
     */
    protected $middleWares = [];

    public function registerMiddleWare(middleWare $middleWare)
    {
        $this->middleWares [get_class($middleWare)] = $middleWare;
    }


}