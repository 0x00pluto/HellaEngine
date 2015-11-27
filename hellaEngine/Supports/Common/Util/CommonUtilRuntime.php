<?php

namespace hellaEngine\Supports\Common\Util;

class CommonUtilRuntime
{
    /**
     * 开始时间
     *
     * @var unknown
     */
    private $StartTime = 0;
    /**
     * 结束时间
     *
     * @var unknown
     */
    private $StopTime = 0;

    private function get_microtime()
    {
        list ($usec, $sec) = explode(' ', microtime());
        return (( float )$usec + ( float )$sec);
    }

    /**
     * 开始
     */
    function start()
    {
        $this->StartTime = $this->get_microtime();
    }

    /**
     * 停止
     */
    function stop()
    {
        $this->StopTime = $this->get_microtime();
    }

    /**
     * 运行时间
     *
     * @return number
     */
    function spent()
    {
        return round(($this->StopTime - $this->StartTime) * 1000, 1);
    }
}