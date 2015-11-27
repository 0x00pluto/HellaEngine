<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/21
 * Time: 上午11:46
 */
use \hellaEngine\Configure\Constants;

return [
    Constants::APP_PATH => "",
    Constants::LOG_PATH => app()->logPath(),
    Constants::DEBUG => true,
    Constants::ONCE_PROCESS_MESSAGE_MAX_COUNT => 8
];