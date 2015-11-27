<?php
namespace hellaEngine\Application\Processes;

use hellaEngine\Configure\Constants;
use hellaEngine\Data\DataBase\DBPools;
use hellaEngine\Interfaces\Application\Processes\ProcessFilter;
use hellaEngine\Services\Base;
use hellaEngine\Supports\Common\Util\CommonUtilLog;
use hellaEngine\Supports\Common\Util\CommonUtilMessage;
use hellaEngine\Supports\Common\Util\CommonUtilReturnVar;
use hellaEngine\Supports\Common\Util\CommonUtilRuntime;
use hellaEngine\Supports\Common\Util\MissingArgumentException;
use hellaEngine\Supports\utils\schedule\Manager;

/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/20
 * Time: 下午7:13
 */
class Processor
{

    /**
     * 消息过滤器
     * @var array
     */
    private $processFilters = [];

    /**
     * 注册消息过滤器
     * @param ProcessFilter $filter
     */
    public function registerProcessFilters(ProcessFilter $filter)
    {
        $this->processFilters[] = $filter;
    }

    private $call_commands = [];


    /**
     * @param ProcessData $ProcessData
     * @return array
     */
    private function process(ProcessData $ProcessData)
    {
        $cmd = $ProcessData->get_command();
        $cmdID = $ProcessData->get_commandid();
        $params = $ProcessData->get_params();
//        $verify = $ProcessData->get_verify();

        $cmdClassName = $ProcessData->get_command_classname();
        $cmdMethodName = $ProcessData->get_command_methodname();

        $functionReturn = CommonUtilReturnVar::RetFail(0, 'no callable');

        $classFullName = route()->getService($cmdClassName);


        //查找注册类
        if (empty($classFullName)) {
            goto end;
        }
        $classIns = new $classFullName;

        //是否是接口类
        if (!$classIns instanceof Base) {
            $functionReturn = CommonUtilReturnVar::RetFail(1, 'service type error!');
            goto end;
        }


        foreach ($this->processFilters as $filter) {
            if ($filter instanceof ProcessFilter)
                if (!$filter->filter($classIns, $ProcessData)) {
                    $functionReturn = CommonUtilReturnVar::RetFail(2, 'not pass filter!');
                    goto end;
                }
        }

        if (!$classIns->is_services_enable($cmdMethodName)) {
            $functionReturn = CommonUtilReturnVar::RetFail(3, 'service cannot call!');
            goto end;
        }

        try {
            $functionReturn = $classIns->call_service($cmdMethodName, $params);
        } catch (MissingArgumentException $e) {
//            $functionReturn = CommonUtilReturnVar::RetFail(err_service_gateway_call::ARGUMENT_ERROR, "MissingArgumentException\n" . $e->getMessage());
        }

        end:

        $returnArr = $functionReturn->to_Array();

        $returnArr [CommonUtilMessage::DBKey_cmd] = $cmd;
        $returnArr [CommonUtilMessage::DBKey_cmdid] = $cmdID;

        return $returnArr;


    }

    private function __processMessage($messageData)
    {
        $timeHelper = new CommonUtilRuntime();
        $timeHelper->start();


        $command = CommonUtilMessage::Message_getCommand($messageData);
        if (empty ($command)) {
            return;
        }

        $processData = ProcessData::create_with_array($command);

        $ret = $this->process($processData);
        $timeHelper->stop();

        CommonUtilMessage::pushS2CMessageBody($ret);

        $this->call_commands [$processData->get_command()] = $timeHelper->spent();
    }


    private function __processMessages($messages)
    {
        $timeHelper = new CommonUtilRuntime ();
        $timeHelper->start();

        if (!is_array($messages)) {
            return "f:" . __LINE__;
        }
        if (count($messages) > config('app')[Constants::ONCE_PROCESS_MESSAGE_MAX_COUNT]) {
            return "f:" . __LINE__;
        }

        // 数据池
        DBPools::default_Db_pools()->begin();


        if (config(Constants::ENABLE_SCHEDULE)) {
            // 定时器调用
            Manager::getInstance()->update();
        }


        foreach ($messages as $value) {
            $this->__processMessage($value);
        }

        DBPools::default_Db_pools()->save();
        DBPools::default_Db_pools()->end();

        // 处理消息返回
        $returnMessages = array();
        while (NULL != ($returnMessage = CommonUtilMessage::popS2CMessage())) {
            $returnMessages [] = $returnMessage->toArray();
        }
        // 没有消息返回
        if (count($returnMessages) == 0) {

            return "f:" . __LINE__;
        }

        $compressedMessage = CommonUtilMessage::encodeMessage($returnMessages);

        $timeHelper->stop();

        CommonUtilLog::record(CommonUtilLog::DEBUG, 'command_run_oncetime', [
            'totaltime' => $timeHelper->spent(),
            'core_time' => array_sum($this->call_commands) . 'ms',
            'cmd' => $this->call_commands
        ]);

        return $compressedMessage;
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


        // 解压缩数据
        $messagesData = CommonUtilMessage::decodeMessage($postData);
        if (is_null($messagesData)) {
            CommonUtilLog::record(CommonUtilLog::ERROR, 'processMessage', [
                $postData,
            ]);
            return "f:" . __LINE__;
        }

        $returnData = $this->__processMessages($messagesData);


        return $returnData;
    }
}