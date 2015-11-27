<?php

namespace hellaEngine\Supports\Common\Util;
use hellaEngine\Interfaces\Data\Serialize;


/**
 *
 * @author zhipeng
 *
 */
class CommonUtilMessage implements Serialize
{

    /**
     * 命令字
     *
     * @var string
     */
    const DBKey_cmd = "cmd";

    /**
     * 命令序号
     *
     * @var string
     */
    const DBKey_cmdid = "cmdid";

    /**
     * 主版本号
     *
     * @var string
     */
    const DBKey_verMajor = "verMajor";

    /**
     * 小版本号
     *
     * @var string
     */
    const DBKey_verMin = "verMin";

    /**
     * 消息类型
     *
     * @var string
     */
    const DBKey_msgType = "msgType";

    /**
     * 消息头
     *
     * @var string
     */
    const DBKey_msghead = "header";

    /**
     * 消息体
     *
     * @var string
     */
    const DBKey_msgdata = "data";
    /**
     * 校验码
     *
     * @var unknown
     */
    const DBKey_verify = "verify";
    /**
     * 参数
     *
     * @var unknown
     */
    const DBKey_params = "params";

    /**
     * 普通消息,未压缩
     *
     * @var unknown
     */
    const MESSAGE_TYPE_DATA = 0;
    /**
     * 普通消息,压缩
     *
     * @var unknown
     */
    const MESSAGE_TYPE_GZIP = 1;

    /**
     * 消息类型错误
     *
     * @var unknown
     */
    const MESSAGE_TYPE_ERROR = 2;

    /**
     * 消息实体
     *
     * @var array
     */
    private $_messagedata = [
        self::DBKey_msghead => [],
        self::DBKey_msgdata => []
    ];

    /**
     * 设置消息大版本号
     *
     * @param integer $value
     */
    public function set_verMajor($value)
    {
        $header = $this->get_header();
        $header [self::DBKey_verMajor] = intval($value);
        $this->set_header($header);
    }

    /**
     * 设置消息小版本号
     *
     * @param integer $value
     */
    public function set_verMin($value)
    {
        $header = $this->get_header();
        $header [self::DBKey_verMin] = intval($value);
        $this->set_header($header);
    }

    /**
     * 设置消息类型
     *
     * @param integer $value
     */
    public function set_messagetype($value)
    {
        $header = $this->get_header();
        $header [self::DBKey_msgType] = intval($value);
        $this->set_header($header);
    }

    /**
     * 设置消息头
     *
     * @param array $value
     */
    private function set_header(array $value)
    {
        $this->_messagedata [self::DBKey_msghead] = $value;
    }

    /**
     * 获取消息头
     *
     * @return array
     */
    public function get_header()
    {
        if (isset ($this->_messagedata [self::DBKey_msghead])) {
            return $this->_messagedata [self::DBKey_msghead];
        }
        return [];
    }

    /**
     * 设置消息体
     *
     * @param array $message
     */
    public function set_messagebody(array $message)
    {
        $this->_messagedata [self::DBKey_msgdata] = $message;
    }

    /**
     * 设置消息体扩展信息
     *
     * @param string $key
     * @param mixed $value
     */
    public function set_messagebody_content($key, $value)
    {
        $messagebody = [];
        if (isset ($this->_messagedata [self::DBKey_msgdata])) {
            $messagebody = $this->_messagedata [self::DBKey_msgdata];
        }

        $messagebody [$key] = $value;
        $this->set_messagebody($messagebody);
    }

    /**
     * 获取消息内容
     *
     * @param string $key
     *
     * @return null|mixed
     */
    public function get_messagebody_content($key)
    {
        $messagebody = [];
        if (isset ($this->_messagedata [self::DBKey_msgdata])) {
            $messagebody = $this->_messagedata [self::DBKey_msgdata];
        }
        if (isset ($messagebody [$key])) {
            return $messagebody [$key];
        }
        return null;
    }

    /*
     * (non-PHPdoc)
     * @see \hellaEngine\data\interfaces\data_interfaces_serialize::toArray()
     */
    function toArray($filter = NULL, $excludefilter = NULL)
    {
        return $this->_messagedata;
    }

    /*
     * (non-PHPdoc)
     * @see \hellaEngine\data\data_interface_serialize::fromArray()
     */
    function fromArray($arr, $exclude = NULL)
    {
        $this->_messagedata = $arr;
    }

    /**
     * 创建消息
     *
     * @param array $messageBody
     * @param int $verMajor
     * @param number $verMin
     * @param number $messageType
     * @return CommonUtilMessage
     */

    static function create(array $messageBody, $verMajor = 1, $verMin = 1, $messageType = self::MESSAGE_TYPE_DATA)
    {
        $ins = new self ();
        $ins->set_verMajor($verMajor);
        $ins->set_verMin($verMin);
        $ins->set_messagetype($messageType);
        $ins->set_messagebody($messageBody);
        return $ins;
    }

    /**
     * 通过创建远程rpc函数生成消息
     *
     * @param string $functionname
     *            远程rpc名称 xxxx.yyyy
     * @param array $params
     *            参数 key=>value
     * @param string $verify
     *            verify
     *
     * @return CommonUtilMessage
     */
    static function create_with_rpccall($functionname, array $params = [], $verify = '')
    {
        $message = self::create([]);
        $message->set_messagebody_content(self::DBKey_cmd, $functionname);
        $message->set_messagebody_content(self::DBKey_cmdid, 1);
        $message->set_messagebody_content(self::DBKey_verify, $verify);
        $message->set_messagebody_content(self::DBKey_params, $params);
        return $message;
    }

    /**
     * 创建messagebody
     *
     * @param string $command
     *            命令关键字
     * @param int $commandid
     *            命令序号
     * @param boolean $retsucc
     *            是否成功
     * @param array $retdata
     *            返回值
     * @param number $retcode
     *            返回代码
     * @return array
     */
    static function createmessagebody_withreturnparam($command, $commandid, $retsucc = true, $retdata = array(), $retcode = 0, $retcode_str = 'SUCC')
    {
        $message = array(
            self::DBKey_cmd => $command,
            self::DBKey_cmdid => $commandid,
            CommonUtilReturnVar::DBKey_retsucc => $retsucc,
            CommonUtilReturnVar::DBKey_retcode => $retcode,
            CommonUtilReturnVar::DBKey_retdata => $retdata,
            CommonUtilReturnVar::DBKey_retcode_str => $retcode_str
        );
        return $message;
    }

    /**
     * 创建消息体
     *
     * @param string $command
     *            命令关键字
     * @param int $commandid
     *            命令序号
     * @param CommonUtilReturnVar $retdata
     * @return multitype:
     */
    static function createmessagebody_withreturndata($command, $commandid, CommonUtilReturnVar $retdata)
    {
        return self::createmessagebody_withreturnparam($command, $commandid, $retdata->get_retsucc(), $retdata->get_retdata(), $retdata->get_retcode(), $retdata->get_retcode_str());
    }

    /**
     * 获取命令
     *
     * @param [type] $messagedata
     *            消息
     */
    static function Message_getCommand($message)
    {
        $MSG_TYPE = array(
            "MSG_DATA" => 0,
            "MSG_GZIP" => 1,
            "MSG_ERROR" => 2
        );
        if ($message == null) {
            return null;
        }

        $header = $message [self::DBKey_msghead];
        if ($header == null) {
            return null;
        }

        $data = $message [self::DBKey_msgdata];
        if ($data == null) {
            return null;
        }

        return $data;
    }

    /**
     * 构建返回消息助手类
     *
     * @param string $command
     * @param array|any $retdata
     * @param number $commandid
     * @param string $retsucc
     * @param number $retcode
     * @param string $retcode_str
     */
    static function pushS2CMessageByCmdId($command, $retdata = [], $commandid = 0, $retsucc = true, $retcode = 0, $retcode_str = 'SUCC')
    {
        self::pushS2CMessageBody(self::createmessagebody_withreturnparam($command, $commandid, $retsucc, $retdata, $retcode, $retcode_str));
    }

    /**
     * 压入消息体
     *
     * @param array $messageBody
     */
    static function pushS2CMessageBody($messageBody)
    {
        CommonUtilMessagePool::defaultMessagePool()->pushMessage(self::create($messageBody));
    }

    /**
     * 弹出消息队列
     *
     * @return CommonUtilMessage
     */
    static function popS2CMessage()
    {
        return CommonUtilMessagePool::defaultMessagePool()->popMessage();
    }

    /**
     * 加密秘钥长度
     *
     * @var unknown
     */
    const NOT_OR_KEY_LEN = 2;

    /**
     * 加密网络消息
     *
     * @param array $message_arr
     *
     * @return string
     */
    static function encodeMessage(array $message_arr)
    {
        $compresseddata = gzcompress(json_encode($message_arr), 9);
        $orkey = "";
        for ($i = 0; $i < self::NOT_OR_KEY_LEN; $i++) {
            $orkey .= chr(mt_rand(1, 200));
        }
        $compressencodestring = $orkey . $compresseddata;
        for ($i = self::NOT_OR_KEY_LEN; $i < strlen($compresseddata) + self::NOT_OR_KEY_LEN; $i++) {
            $compressencodestring [$i] = $compressencodestring [$i - self::NOT_OR_KEY_LEN] ^ $compressencodestring [$i];
        }
        $compressmessage = base64_encode($compressencodestring);
        return $compressmessage;
    }

    /**
     * 解密网络消息
     *
     * @param string $message_str
     * @return array|NULL
     */
    static function decodeMessage($message_str)
    {
        try {
            // 解压缩数据
            $unbase64 = base64_decode($message_str);
            if ($unbase64 === FALSE) {
                return null;
            }

            // 解密
            for ($i = strlen($unbase64) - 1; $i >= self::NOT_OR_KEY_LEN; $i--) {
                $unbase64 [$i] = $unbase64 [$i - self::NOT_OR_KEY_LEN] ^ $unbase64 [$i];
            }

            $unbase64 = substr($unbase64, self::NOT_OR_KEY_LEN);
            $messagesdata = gzuncompress($unbase64);
            if ($messagesdata === FALSE) {
                return null;
            }

            $messages = json_decode($messagesdata, true);
        } catch (Exception $e) {
            return null;
        }

        return $messages;
    }
}