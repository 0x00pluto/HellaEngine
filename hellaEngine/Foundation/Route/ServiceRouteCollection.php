<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/17
 * Time: 下午5:06
 */

namespace hellaEngine\Foundation\Route;


/**
 * Clas ServiceRoute
 * @package hellaEngine\Foundation\Route
 */
class ServiceRouteCollection extends RouteCollection
{

    /**
     * 增加服务
     * @param string $ServiceName 类名 例如 help.helpWith 的help
     * @param string $ServiceClassName 类全名 包含命名空间
     */
    public function addService($ServiceName, $ServiceClassName)
    {
        $this->add($ServiceName, new Route($ServiceClassName));
    }

    public function getService($ServiceName)
    {
        $route = $this->get($ServiceName);
        if (is_null($route)) {
            return null;
        }
        return $route->getPath();
    }

    /**
     * 获取全部路由
     * @return array
     */
    public function all()
    {
        $all = [];
        foreach ($this->routes as $key => $route) {
            if ($route instanceof Route) {
                $all[$key] = $route->getPath();
            }
        }
        return $all;
    }


}