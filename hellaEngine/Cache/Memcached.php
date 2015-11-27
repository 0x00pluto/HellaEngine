<?php

namespace hellaEngine\Cache;

use hellaEngine\Constants\Configure\Config;


/**
 * Memcache 操作类
 *
 * 在config文件中 添加
 * 相应配置(可扩展为多memcache server)
 * define('MEMCACHE_HOST', '10.35.52.33');
 * define('MEMCACHE_PORT', 11211);
 * define('MEMCACHE_EXPIRATION', 0);
 * define('MEMCACHE_PREFIX', 'licai');
 * define('MEMCACHE_COMPRESSION', FALSE);
 * demo:
 * $cacheObj = new Common_Db_memcached();
 * $cacheObj -> set('keyName','this is value');
 * $cacheObj -> get('keyName');
 * exit;
 *
 * @access public
 * @return object @date 2012-07-02
 */
class Memcached
{
    private $local_cache = array();
    /**
     *
     * @var \Memcached
     */
    private $m;
    private $client_type;
    protected $errors = array();
    // 保存类实例的静态成员变量
    private static $_instance;

    // 单例方法,用于访问实例的公共的静态方法
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

    private function __construct()
    {
        $fristclassname = 'Memcached';
        $secondclassname = 'Memcache';

        $this->client_type = class_exists($fristclassname) ? $fristclassname : (class_exists($secondclassname) ? $secondclassname : FALSE);
        if ($this->client_type) {
            // 判断引入类型
            switch ($this->client_type) {
                case 'Memcached' :
                    $this->m = new \Memcached ('hella_connect');
                    if (count($this->m->getServerList()) == 0) {
                        $this->m->setOption(\Memcached::OPT_COMPRESSION, false); // 关闭压缩功能
                        $this->m->setOption(\Memcached::OPT_BINARY_PROTOCOL, true); // 使用binary二进制协议
                    } else {
                        // functionsDump ( $this->m->getServerList () );
                    }

                    break;
                case 'Memcache' :
                    $this->m = new \Memcache ();
                    // if (auto_compress_tresh){
                    // $this->setcompressthreshold(auto_compress_tresh, auto_compress_savings);
                    // }
                    break;
            }
            $this->auto_connect();
        } else {
            echo 'ERROR: Failed to load Memcached or Memcache Class (∩_∩)';
            exit ();
        }
    }

    /**
     * @Name: auto_connect
     *
     * @param
     *            :none
     * @todu 连接memcache server
     * @return : none
     *         add by cheng.yafei
     *
     */
    /**
     * 自动连接到Memcached
     */
    private function auto_connect()
    {
        $configServer = array(
            'host' => C(Config::MEMCACHE_HOST),
            'port' => C(Config::MEMCACHE_PORT),
            'weight' => 1
        );

        if (!$this->add_server($configServer)) {
            echo 'ERROR: Could not connect to the server named ' . C(Config::MEMCACHE_HOST);
        } else {
            // echo 'SUCCESS:Successfully connect to the server named '.MEMCACHE_HOST;
        }
    }

    /**
     * @Name: add_server
     *
     * @param
     *            :none
     * @todu 连接memcache server
     * @return : TRUE or FALSE
     *         add by cheng.yafei
     *
     */
    public function add_server($server)
    {
        if (count($this->m->getServerList()) == 0) {
            extract($server);
            return $this->m->addServer($host, $port, $weight);
        }
        return true;
    }


    /**
     * 添加数据
     * @param string|array $key
     * @param mixed $value
     * @param int $expiration
     * @return array|bool
     */
    public function add($key = NULL, $value = NULL, $expiration = 0)
    {
        if (is_null($expiration)) {
            $expiration = C(Config::MEMCACHE_EXPIRATION);
        }
        $expiration = intval($expiration);


        if (is_array($key)) {
            foreach ($key as $multi) {
                if (!isset ($multi ['expiration']) || $multi ['expiration'] == '') {
                    $multi ['expiration'] = $expiration;
                }
                $this->add($this->key_name($multi ['key']), $multi ['value'], $multi ['expiration']);
            }
            $add_status = true;
        } else {
            $keyName = $this->key_name($key);
            $this->local_cache [$keyName] = $value;
            switch ($this->client_type) {
                case 'Memcache' :
                    $add_status = $this->m->add($keyName, $value, C(Config::MEMCACHE_COMPRESSION), $expiration);
                    break;

                default :
                case 'Memcached' :

                    $add_status = $this->m->add($keyName, $value, $expiration);
                    break;
            }

        }
        return $add_status;
    }

    /**
     * @Name 与add类似,但服务器有此键值时仍可写入替换
     *
     * @param string $key key
     * @param mixed $value 值
     * @param int $expiration 过期时间
     * @return TRUE or FALSE
     *         add by cheng.yafei
     *
     */
    public function set($key = NULL, $value = NULL, $expiration = NULL)
    {
        if (is_null($expiration)) {
            $expiration = C(Config::MEMCACHE_EXPIRATION);
        }
        if (is_array($key)) {
            foreach ($key as $multi) {
                if (!isset ($multi ['expiration']) || $multi ['expiration'] == '') {
                    $multi ['expiration'] = $expiration;
                }
                $this->set($this->key_name($multi ['key']), $multi ['value'], $multi ['expiration']);
            }
            $add_status = true;
        } else {
            $this->local_cache [$this->key_name($key)] = $value;
            $add_status = false;
            switch ($this->client_type) {
                case 'Memcache' :
                    $add_status = $this->m->set($this->key_name($key), $value, C(Config::MEMCACHE_COMPRESSION), $expiration);
                    break;
                case 'Memcached' :
                    $add_status = $this->m->set($this->key_name($key), $value, $expiration);
                    break;
            }
        }
        return $add_status;
    }

    /**
     * @Name get 根据键名获取值
     *
     * @param string $key key
     * @return array OR json object OR string...
     *         add by cheng.yafei
     *
     */
    public function get($key = NULL)
    {
        if ($this->m) {
            $keyName = $this->key_name($key);
            if (isset ($this->local_cache [$keyName])) {
                return $this->local_cache [$keyName];
            }
            if (is_null($key)) {
                $this->errors [] = 'The key value cannot be NULL';
                return false;
            }

            if (is_array($key)) {
                foreach ($key as $n => $k) {
                    $key [$n] = $this->key_name($k);
                }
                return $this->m->getMulti($key);
            } else {

                $obj = $this->m->get($keyName);
                $this->local_cache [$keyName] = $obj;
                return $obj;
            }
        } else {
            return false;
        }
    }

    /**
     * @Name delete
     *
     * @param string|array $key key
     * @param int|null $expiration 服务端等待删除该元素的总时间
     * @return true OR false
     *         add by cheng.yafei
     *
     */
    public function delete($key, $expiration = NULL)
    {
        if (is_null($key)) {
            $this->errors [] = 'The key value cannot be NULL';
            return FALSE;
        }

        if (is_null($expiration)) {
            $expiration = C(Config::MEMCACHE_EXPIRATION);
        }

        if (is_array($key)) {
            foreach ($key as $multi) {
                $this->delete($multi, $expiration);
            }
            return true;
        } else {
            unset ($this->local_cache [$this->key_name($key)]);
            return $this->m->delete($this->key_name($key), $expiration);
        }
    }

    /**
     * @Name replace
     *
     * @param string|array $key 要替换的key
     * @param mixed $value 要替换的value
     * @param int|null $expiration 到期时间
     * @return bool add by cheng.yafei
     *
     */

    public function replace($key = NULL, $value = NULL, $expiration = NULL)
    {
        if (is_null($expiration)) {
            $expiration = C(Config::MEMCACHE_EXPIRATION);
        }
        if (is_array($key)) {
            foreach ($key as $multi) {
                if (!isset ($multi ['expiration']) || $multi ['expiration'] == '') {
                    $multi ['expiration'] = $expiration;
                }
                $this->replace($multi ['key'], $multi ['value'], $multi ['expiration']);
            }
            $replace_status = true;
        } else {
            $this->local_cache [$this->key_name($key)] = $value;

            $replace_status = false;

            switch ($this->client_type) {
                case 'Memcache' :
                    $replace_status = $this->m->replace($this->key_name($key), $value, C(Config::MEMCACHE_COMPRESSION), $expiration);
                    break;
                case 'Memcached' :
                    $replace_status = $this->m->replace($this->key_name($key), $value, $expiration);
                    break;
            }

        }
        return $replace_status;
    }

    /**
     * @Name replace 清空所有缓存
     *
     * @return bool add by cheng.yafei
     *
     */
    public function flush()
    {
        return $this->m->flush();
    }

    /**
     * @Name 获取服务器池中所有服务器的版本信息
     */
    public function getVersion()
    {
        return $this->m->getVersion();
    }

    /**
     * 获取服务器池的统计信息
     * @param string $type
     * @return array|bool
     */
    public function getStats($type = "items")
    {
        switch ($this->client_type) {
            case 'Memcache' :
                $stats = $this->m->getStats($type);
                break;

            default :
            case 'Memcached' :
                $stats = $this->m->getStats();
                break;
        }
        return $stats;
    }

    /**
     * 开启大值自动压缩
     * @param int $tresh 控制多大值进行自动压缩的阈值。
     * @param float $savings 指定经过压缩实际存储的值的压缩率，值必须在0和1之间。默认值0.2表示20%压缩率。
     * @return bool
     */
    public function setcompressthreshold($tresh, $savings = 0.2)
    {
        switch ($this->client_type) {
            case 'Memcache' :
                $setcompressthreshold_status = $this->m->setCompressThreshold($tresh, $savings);
                break;

            default :
                $setcompressthreshold_status = TRUE;
                break;
        }
        return $setcompressthreshold_status;
    }

    /**
     * 生成md5加密后的唯一键值
     * @param string $key
     * @return string
     */
    public function key_name($key)
    {
        return md5(strtolower(C(Config::MEMCACHE_PREFIX) . $key));
    }

    /**
     * 向已存在元素后追加数据
     * @param string|array $key
     * @param mixed $value
     */
    public function append($key = NULL, $value = NULL)
    {
        $this->local_cache [$this->key_name($key)] = $value;

        switch ($this->client_type) {
            case 'Memcache' :
                $append_status = $this->m->append($this->key_name($key), $value);
                break;

            default :
            case 'Memcached' :
                $append_status = $this->m->append($this->key_name($key), $value);
                break;
        }

        return $append_status;
    } // END append
} // END class