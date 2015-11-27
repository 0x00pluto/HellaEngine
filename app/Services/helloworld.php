<?php

namespace app\Services;
use hellaEngine\Supports\Common\Util\CommonUtilReturnVar;


/**
 * @auther zhipeng
 */
class helloworld extends Base
{
    function __construct()
    {
        $this->services_enable(array(
            'helloworld'
        ));
    }
    // protected function get_dbins() {
    // return $this->callerUserInstance->dbs_name ();
    // }
    // protected function get_err_class_name() {
    // return "err\\"."err_dbs_name"."_";
    // }
    /**
     *
     * @param string $hello
     * @return CommonUtilReturnVar
     */
    function helloworld($hello)
    {
        $retCode = 0;
        $retCode_Str = 'SUCC';
        $data = array();

        // class err_service_name_helloworld{}

        // code

        succ:
        return CommonUtilReturnVar::Ret(true, $retCode, $data, $retCode_Str);
        failed:
        return CommonUtilReturnVar::Ret(false, $retCode, $data, $retCode_Str);
    }
}