<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/17
 * Time: 下午5:31
 */

namespace hellaEngine\Supports;


use Illuminate\Support\Str;

class Property
{

    public function hasGetMutator($key)
    {
        return method_exists($this, 'get' . Str::studly($key) . 'Property');
    }

    protected function mutateAttribute($key, $value)
    {
        return $this->{'get' . Str::studly($key) . 'Property'}($value);
    }


    public function getProperty($key)
    {

    }

    public function setProperty($key, $value)
    {

    }
}