<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/17
 * Time: 下午5:10
 */

namespace hellaEngine\Foundation\Route;


class RouteCollection
{

    /**
     * @var array
     */
    protected $routes = [];

    /**
     * @param $path
     * @param Route $route
     */
    public function add($path, Route $route)
    {
        unset($this->routes[$path]);
        $this->routes[$path] = $route;
    }

    /**
     * @param $path
     * @return null|Route
     */
    public function get($path)
    {
        if (isset($this->routes[$path])) {
            return $this->routes[$path];
        }
        return null;
    }

    /**
     * 获取全部路由
     * @return array
     */
    public function all()
    {
        return $this->routes;
    }


}