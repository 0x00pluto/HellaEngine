<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/22
 * Time: 下午12:11
 */

namespace hellaEngine\Interfaces\Data;


interface BaseDataCell extends Base
{
    /**
     * 导出成数组
     * @param null $filter
     * @param null $excludeFilter
     * @return array
     */
    public function toArray($filter = NULL, $excludeFilter = NULL);

    /**
     * 从数组导入数据
     * @param array $arr
     * @param null $exclude
     * @return bool
     */
    public function fromArray($arr, $exclude = NULL);
}