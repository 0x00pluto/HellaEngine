<?php

namespace hellaEngine\Application\Processes;
use hellaEngine\Data\BaseDataCell;


/**
 * 说明
 * 2015年7月31日 下午4:58:05
 *
 * @author zhipeng
 *
 */
class ProcessData extends BaseDataCell
{
    /**
     * 命令
     *
     * @var string
     */
    const DBKey_command = "cmd";

    /**
     * 获取命令
     * @return mixed
     */
    public function get_command()
    {
        return $this->getdata(self::DBKey_command);
    }

    /**
     * 设置 命令
     *
     * @param unknown $value
     */
    public function set_command($value)
    {
        $value = strval($value);
        $this->setdata(self::DBKey_command, $value);
    }

    /**
     * 设置 命令 默认值
     */
    protected function _set_defaultvalue_command()
    {
        $this->set_defaultkeyandvalue(self::DBKey_command, '');
    }

    /**
     * 命令id
     *
     * @var string
     */
    const DBKey_commandid = "cmdid";

    /**
     * 获取 命令id
     */
    public function get_commandid()
    {
        return $this->getdata(self::DBKey_commandid);
    }

    /**
     * 设置 命令id
     *
     * @param unknown $value
     */
    public function set_commandid($value)
    {
        $value = intval($value);
        $this->setdata(self::DBKey_commandid, $value);
    }

    /**
     * 设置 命令id 默认值
     */
    protected function _set_defaultvalue_commandid()
    {
        $this->set_defaultkeyandvalue(self::DBKey_commandid, 0);
    }

    /**
     * 参数
     *
     * @var string
     */
    const DBKey_params = "params";

    /**
     * 获取 参数
     */
    public function get_params()
    {
        return $this->getdata(self::DBKey_params);
    }

    /**
     * 设置 参数
     *
     * @param unknown $value
     */
    public function set_params($value)
    {
        // $value = strval($value);
        $this->setdata(self::DBKey_params, $value);
    }

    /**
     * 设置 参数 默认值
     */
    protected function _set_defaultvalue_params()
    {
        $this->set_defaultkeyandvalue(self::DBKey_params, array());
    }

    /**
     * verify
     *
     * @var string
     */
    const DBKey_verify = "verify";

    /**
     * 获取 verify
     */
    public function get_verify()
    {
        return $this->getdata(self::DBKey_verify);
    }

    /**
     * 设置 verify
     *
     * @param unknown $value
     */
    public function set_verify($value)
    {
        $value = strval($value);
        $this->setdata(self::DBKey_verify, $value);
    }

    /**
     * 设置 verify 默认值
     */
    protected function _set_defaultvalue_verify()
    {
        $this->set_defaultkeyandvalue(self::DBKey_verify, '');
    }

    /**
     * 获取命令类名
     *
     * @return NULL|string
     */
    public function get_command_classname()
    {
        $cmd = $this->get_command();
        if (empty ($cmd)) {
            return null;
        }
        $cmdarr = explode(".", $cmd);
        if (count($cmdarr) != 2) {
            return null;
        }
        return trim($cmdarr [0]);
    }

    /**
     * 获取命令方法名
     *
     * @return NULL|string
     */
    public function get_command_methodname()
    {
        $cmd = $this->get_command();
        if (empty ($cmd)) {
            return null;
        }
        $cmdarr = explode(".", $cmd);
        if (count($cmdarr) != 2) {
            return null;
        }
        return trim($cmdarr [1]);
    }
}