<?php

namespace hellaEngine\Interfaces\Data;

/**
 * 数据序列化接口
 *
 * @author zhipeng
 *
 */
interface Serialize
{
    /**
     * 获取原始数据
     *
     * @return array
     */
    public function toArray();

    /**
     * 反序列化
     *
     * @param array $arr
     *            不同步的字段
     *            ('key'=>1)
     */
    public function fromArray($arr);
}