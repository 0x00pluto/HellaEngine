<?php

namespace hellaEngine\Supports\Common\Util;

use configdata\configdata_global_config;
use configdata\configdata_server_lang_setting;

class CommonUtilConfigdata
{
    /**
     * singleton
     */
    private static $_instance;

    private function __construct()
    {
        // echo 'This is a Constructed method;';
    }

    public function __clone()
    {
        trigger_error('Clone is not allow!', E_USER_ERROR);
    }

    /**
     *
     * @return CommonUtilConfigdata
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

    private $__dataindex = array();

    /**
     * 创建缓存索引
     *
     * @param classname $configdataclass
     *            数据表
     * @param string $index
     *            索要
     * @return multitype: array 建立索要类的Dict
     */
    public function buildconfigdata($configdataclass, $index)
    {
        $configdataclass = strval($configdataclass);
        $index = strval($index);
        if (array_key_exists($configdataclass, $this->__dataindex)) {
            return $this->__dataindex [$configdataclass];
        }
        $data_contains = array();
        foreach ($configdataclass::data() as $value) {
            $data_contains [$value [$index]] = $value;
        }
        $this->__dataindex [$configdataclass] = $data_contains;
        return $this->__dataindex [$configdataclass];
    }

    /**
     * 获取配置数据
     *
     * @param string $configdataclass
     * @param string $index
     * @param string $key
     * @param string $default_value
     * @return multitype:|string
     */
    public function getconfigdata($configdataclass, $index, $key, $default_value = NULL)
    {
        $data_contains = $this->buildconfigdata($configdataclass, $index);
        // functionsDump ( $data_contains );
        $key = strval($key);
        if (isset ($data_contains [$key])) {
            return $data_contains [$key];
        }
        return $default_value;
    }

    /**
     * 获取全局配置
     *
     * @param string $key
     * @param string $defaultvalue
     */
    public function get_global_config($key, $defaultvalue = NULL)
    {
        $value = $this->getconfigdata(configdata_global_config::class, "key", $key, NULL);
        if (is_null($value)) {
            return $defaultvalue;
        } else {
            return $value ['value'];
        }
    }

    /**
     * 获取全局配置
     *
     * @param string $key
     * @param string $defaultvalue
     * @return CommonUtilValue
     */
    public function get_global_config_value($key, $defaultvalue = NULL)
    {
        return CommonUtilValue::build($this->get_global_config($key, $defaultvalue));
    }

    /**
     * 获取语言
     *
     * @param unknown $langid
     * @param unknown $params
     *            replacekey {'from'=>'to'}
     * @param string $locate
     * @return string|Ambigous <string, string>
     */
    public function get_lang($langid, array $params = array(), $locate = 'zn')
    {
        $conf = $this->getconfigdata(configdata_server_lang_setting::class, configdata_server_lang_setting::k_languageid, $langid);
        if (is_null($conf)) {
            return "";
        }
        $langstr = "";

        if (isset ($conf [$locate])) {
            $langstr = $conf [$locate];
            $langstr = strtr($langstr, $params);
        }

        return $langstr;
    }
}

