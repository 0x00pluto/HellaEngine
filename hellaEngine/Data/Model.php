<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/9
 * Time: 下午7:08
 */

namespace hellaEngine\Data;


use Illuminate\Support\Collection;

/**
 * 数据模型
 * Class Model
 * @package hellaEngine\Data
 */
class Model extends Collection implements \hellaEngine\Interfaces\Data\Model
{

    /**
     * 反序列化
     *
     * @param array $arr
     *            不同步的字段
     *            ('key'=>1)
     */
    public function fromArray($arr)
    {
    }


//    /**
//     *
//     * @return static
//     */
//    static function Builder()
//    {
//        return new static();
//    }


}