<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/20
 * Time: 下午5:33
 */

namespace hellaEngine\Application;

use hellaEngine\Application\Processes\Processor;
use hellaEngine\Events\Event;
use hellaEngine\Events\EventDispatcher;
use hellaEngine\Foundation\Route\ServiceRouteCollection;
use hellaEngine\interfaces\Application\Processes\ProcessFilter;
use Illuminate\Container\Container;

/**
 * 应用基础类
 * Class Application
 * @package hellaEngine\Application
 */
class Application extends Container
{
    /**
     * Application constructor.
     * @param $basePath
     */
    public function __construct($basePath = null)
    {
        $this->registerBaseBindings();

        $this->registerBaseServices();

        $this->registerCoreContainerAliases();

        $this->setBasePath($basePath);

        $this->processor = new Processor();
    }

    /**
     * @var Processor
     */
    private $processor;


    /**
     * 程序基础路径
     * @var string
     */
    protected $basePath;

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');
    }


    /**
     * 返回程序路径
     * @return string
     */
    public function appPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'app';
    }

    /**
     * 返回配置目录
     * @return string
     */
    public function configPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'configure';
    }

    /**
     * 返回日志目录
     * @return string
     */
    public function logPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'logs';
    }

    /**
     * 换回Public目录
     * @return string
     */
    public function publicPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'public';
    }

    /**
     *
     *
     * @RETURN STRING
     */
    public function cachePath()
    {
        return $this->publicPath() . DIRECTORY_SEPARATOR . 'caches';
    }


    /**
     * 处理消息
     *
     * @param string $postData
     *            客户端传上了的 经过压缩和Base64 编码的数据
     * @return array 返回客户端的数据
     */
    public function processMessage($postData)
    {

        if (empty ($postData)) {
            return "f:" . __LINE__;
        }


        $this->beforeProcessMessage($postData);


        $returnData = $this->processor->processMessage($postData);


        $this->afterProcessMessage($postData);

        return $returnData;
    }

    /**
     * 开始处理消息组前
     * @param $postData
     */
    protected function beforeProcessMessage($postData)
    {
        eventDispatcher()->dispatch('beforeProcessMessage', new Event("beforeProcessMessage", [
            'postData' => $postData
        ]));
    }

    /**
     * 开始处理消息组后
     * @param $postData
     */
    protected function afterProcessMessage($postData)
    {
        

    }


    /**
     * 配置服务
     */
    protected function configure()
    {

    }


    /**
     * 默认开启服务
     * @return array
     */
    protected function DefaultServices()
    {
        route()->addService('help', 'hellaEngine\Services\help');
    }


    private $bInitialize = false;

    /**
     * 初始化
     */
    final public function initialize()
    {
        if ($this->bInitialize) {
            return;
        }
        $this->bInitialize = true;

        $this->initializeWithBootstrappers();

        $this->configure();

        $this->DefaultServices();

        //注册消息处理器
        $this->processor = new Processor();
    }


    /**
     * 注册消息过滤器??
     * 中间件...
     * @param ProcessFilter $filter
     */
    public function registerProcessFilters(ProcessFilter $filter)
    {
        $this->processor->registerProcessFilters($filter);
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance('Illuminate\Container\Container', $this);
    }

    protected function registerBaseServices()
    {
        $this->singleton('events', function ($app) {
            return new EventDispatcher();
        });
        $this->instance('serviceRoute', new ServiceRouteCollection());


    }


    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    protected function registerCoreContainerAliases()
    {

        $aliases = [
            'app' => 'hellaEngine\Application\Application',
            'events' => 'hellaEngine\Events\EventDispatcher',
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ((array)$aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }


    protected $bootstrappers = [
        'hellaEngine\Foundation\Bootstraps\LoadConfigure',
        'hellaEngine\Foundation\Bootstraps\LoadServiceRoute'
    ];

    /**
     * 初始化其它加载器
     */
    private function initializeWithBootstrappers()
    {

        foreach ($this->bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->bootstrap($this);
        }
    }


}