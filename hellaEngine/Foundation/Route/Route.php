<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/17
 * Time: 下午5:05
 */

namespace hellaEngine\Foundation\Route;


/**
 * 路由类
 * Class Route
 * @package hellaEngine\Foundation\Route
 */
class Route
{
    private $path;

    /**
     * Route constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

}