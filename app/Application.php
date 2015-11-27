<?php

namespace app;

/**
 * do nothing
 *
 * @author lijiang
 */
class Application extends \hellaEngine\Application\Application
{
    private $profileEnable = false;

    /**
     * 配置服务
     */
    protected function configure()
    {
        eventDispatcher()->addListener('beforeProcessMessage',
            function ($e) {
                $this->beforeProcessMessageEvent($e);
            }
        );
    }


    /**
     * @param $e
     */
    protected function beforeProcessMessageEvent($e)
    {
//        dump($e);
//        dump($this['events']);
//        dump($this['events']);
//        $this->profileEnable = C(\configure_constants::PHP_PROFILE);
//
//        if (C(\configure_constants::PHP_PROFILE) && function_exists('xhprof_enable')) {
//            $this->profileEnable = true;
//        }
//        if ($this->profileEnable) {
//            \xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
//        }
    }

    /**
     * 开始处理消息组后
     * @param $postData
     */
    protected function afterProcessMessage($postData)
    {
//        if ($this->profileEnable) {
//            $xhprof_data = xhprof_disable();
//            include_once "./xhprof_lib/utils/xhprof_lib.php";
//            include_once "./xhprof_lib/utils/xhprof_runs.php";
//            $xhprof_runs = new XHProfRuns_Default ();
//            $run_id = $xhprof_runs->save_run($xhprof_data, 'xhprof');
//            if (C(\configure_constants::DEBUG)) {
//                echo '<a href="http://' . $_SERVER ['HTTP_HOST'] . '/xhprof_html/index.php?run=' . $run_id . '&source=xhprof" target="_blank">性能分析</a>' . "\n"; // source的值就是save_run的第二个参数的值。其中，网址就是上面保存xhprof_html的路径。
//            } else {
//            }
//        }
    }

    /**
     * 默认开启服务
     * @return array
     */
//    protected function DefaultServices()
//    {
//        $Services = parent::DefaultServices();
//        $Services['helloworld'] = 'app\Services\helloworld';
//        return $Services;
//    }


}


