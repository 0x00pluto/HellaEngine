<?php

namespace hellaEngine\Services;


use hellaEngine\Supports\Common\Util\CommonUtilReference;
use hellaEngine\Supports\Common\Util\CommonUtilReturnVar;
use hellaEngine\Constants\Services\help as helpConstants;

class help extends Base
{


    function __construct()
    {
        $this->services_enable(array(
            'help'
        ), true);
    }

    public function isNeedLogin()
    {
        return false;
    }


    private function buildServices()
    {
        $Services = route()->all();
        $OutputServices = [];

        foreach ($Services as $ServiceName => $ServerClassName) {
            if ($ServerClassName == __CLASS__) {
                continue;
            }

            $className = $ServerClassName;
            $shortName = $ServiceName;

            $ref = new \ReflectionClass ($className);
            // 虚类
            if ($ref->isAbstract()) {
                continue;
            }

            $serviceInfo = [];
            $serviceInfo [helpConstants::CLASSNAME] = $className;
            $serviceInfo [helpConstants::SHORT_CLASSNAME] = $shortName;
            $serviceInfo [helpConstants::DOCCOMMENTS] = $ref->getDocComment();


            $serviceInfo [helpConstants::SERVICES] = $this->buildService($className);

            $OutputServices[$className] = $serviceInfo;

        }

        return $OutputServices;
    }

    /**
     * 生成对应的说明文档
     *
     * @return CommonUtilReturnVar
     */
    private function buildService($className)
    {
        $arr = array();

        $classIns = new $className ();
        if (!$classIns instanceof Base) {
            return $arr;
        }

        $refClass = new \ReflectionClass ($className);
        $shortClassName = $refClass->getShortName();
        // 类真实名称
//        $classrealname = explode("_", $shortClassName) [1];

        $arr [helpConstants::CLASSNAME] = $className;
        $arr [helpConstants::SHORT_CLASSNAME] = $shortClassName;

        $services = array();

        foreach ($classIns->service_list as $methodName => $serviceData) {
            $service = array();

            $propertys = $refClass->getMethod($methodName);
            $service [helpConstants::DOCCOMMENTS] = $propertys->getDocComment();
            $service [helpConstants::SERVICE_DATA] = $serviceData;
            // retCode Name
            // 通过函数名称自动拼接
            $retCode_contants = NULL;
            $ref_retCode = null;
//
//            $retCodeAutoClassname = C(helpConstants::APP_NAMESPACE) . "\\err\\err_" . $shortClassName . "_" . $methodName;
//            if (class_exists($retCodeAutoClassname)) {
//                $ref_retCode = new \ReflectionClass ($retCodeAutoClassname);
//                $retCode_contants = $ref_retCode->getConstants();
//            } else {
//                // 查找对应的dbs寻找返回值
//                $retCodedbsClassname = C(helpConstants::APP_NAMESPACE) . "\\err\\err_" . "dbs_" . $classrealname . "_" . $methodName;
//                if (class_exists($retCodedbsClassname)) {
//                    $ref_retCode = new \ReflectionClass ($retCodedbsClassname);
//                    $retCode_contants = $ref_retCode->getConstants();
//                } else {
//                    // 具体错误的类
//                    $err_classname_prefix = $this->get_err_class_name();
//                    if (!empty ($err_classname_prefix)) {
//                        $err_classname = $err_classname_prefix . $methodName;
//                        if (class_exists($err_classname)) {
//
//                            $ref_retCode = new \ReflectionClass ($err_classname);
//                            $retCode_contants = $ref_retCode->getConstants();
//                        }
//                    }
//                }
//            }

            if (!is_null($retCode_contants)) {

                $constants_comments = CommonUtilReference::getConstDocument($ref_retCode->getName());
                $retcodeString = "";
                foreach ($retCode_contants as $key => $value) {

                    if (isset ($constants_comments [$key])) {
                        $retcodeString .= $constants_comments [$key] . "<br>";
                    }
                    $retcodeString .= "YYNet_" . $shortClassName . "_" . $propertys->name . "_RetCode." . $key . "=" . $value . "<br>";
                }

                $service [helpConstants::RETCODECOMMENTS] = $retcodeString;
            }


            $service [helpConstants::FUNCTIONNAME] = $propertys->name;
            $params = array();
            foreach ($propertys->getParameters() as $value) {
                $params [] = $value->name;
            }
            $service [helpConstants::FUNCTIONPARAMS] = $params;


            $services [$service [helpConstants::FUNCTIONNAME]] = $service;
        }

        $arr [helpConstants::SERVICES] = $services;

        return $arr;
    }

    /**
     * @param bool|true $isdump
     * @return CommonUtilReturnVar
     */
    public function help($isdump = true)
    {
        $retCode = 0;
        $retCode_Str = 'SUCC';
        $data = array();


        $Services = $this->buildServices();

        sort($Services);

        $this->_gen_html_docments($Services);

//        dump($Services);

        succ:
        return CommonUtilReturnVar::Ret(true, $retCode, $data, $retCode_Str);
        failed:
        return CommonUtilReturnVar::Ret(false, $retCode, $data, $retCode_Str);

    }


    /**
     * 生成说明文档
     * @param $services
     */
    private function _gen_html_docments($services)
    {
        if (!is_dir(app()->cachePath())) {
            mkdir(app()->cachePath(), 0777, true);
        }

        $shorthtmlfilename = "documents_services_description.html";
        $htmlfilename = app()->cachePath() . DIRECTORY_SEPARATOR . $shorthtmlfilename;


        $handle = fopen($htmlfilename, "w");
        if (!$handle) {
            dump("open:" . $htmlfilename . " error!");
            return;
        }


        $contents = "<html>\n";
        $contents .= '<head><title>餐厅服务器API文档</title>';
        $contents .= '<style type="text/css">
a:link,a:visited{
 text-decoration:none;  /*超链接无下划线*/
}
a:hover{
 text-decoration:underline;  /*鼠标放上去有下划线*/
}
</style>';
        $contents .= '</head>';
        $contents .= "<body>\n";
        $contents .= '<h1>餐厅服务器API文档 </h1>';
        $contents .= '<h2>生成日期:' . date("c") . '</h2>';

        $contents .= '<h3>通过调用 <a href=http://' . $_SERVER ['HTTP_HOST'] . '/test.php?functionname=help.help&backurl=caches/' . $shorthtmlfilename . '>help.help</a> 生成</h3>';
        $contents .= '<h3>通过调用 <a href=http://' . $_SERVER ['HTTP_HOST'] . '/test.php?functionname=help.dumpcode&backurl=caches/' . $shorthtmlfilename . '>help.dumpcode</a> 生成客户端代码 <a href=http://' . $_SERVER ['HTTP_HOST'] . '/lua_code/lua_code.zip>接口代码下载</a></h3>';

        $contents .= '<hr/>';
        // backurl
        // http://' . $_SERVER ['HTTP_HOST'] . '/test.php?

        $tables = "<table border=\"0\">";
        $tables .= "<tr>";
        $tables .= "<th>接口名称</th>";
        $tables .= "<th>接口描述</th>";
        $tables .= "<th>接口名称</th>";
        $tables .= "<th>接口描述</th>";
        $tables .= "<th>接口名称</th>";
        $tables .= "<th>接口描述</th>";

        $tables .= "</tr>";

        $servicecount = count($services) / 3;

        for ($i = 0; $i < $servicecount; $i++) {

            $value = $services [$i * 3];
            $nextvalue = $services [$i * 3 + 1];
            $thirdnextvalue = $services [$i * 3 + 2];

            if ($i % 2 == 0) {
                $bgcolor = '"#FFFFFF"';
            } else {
                $bgcolor = '"#CCCCCC"';
            }

            $tables .= "<tr bgcolor=" . $bgcolor . ">";

            $key = $value [helpConstants::CLASSNAME];
            $tables .= "<td height=\"30\"><font color=\"#FF0000\"><a href=\"#" . $key . "\">" . $value [helpConstants::SHORT_CLASSNAME] . "</a></font></td>";
            $tables .= "<td height=\"30\"><font color=\"#006600\">" . $value [helpConstants::DOCCOMMENTS] . "</font></td>";

            $key = $nextvalue [helpConstants::CLASSNAME];
            $tables .= "<td height=\"30\"><font color=\"#FF0000\"><a href=\"#" . $key . "\">" . $nextvalue [helpConstants::SHORT_CLASSNAME] . "</a></font></td>";
            $tables .= "<td height=\"30\"><font color=\"#006600\">" . $nextvalue [helpConstants::DOCCOMMENTS] . "</font></td>";

            $key = $thirdnextvalue [helpConstants::CLASSNAME];
            $tables .= "<td height=\"30\"><font color=\"#FF0000\"><a href=\"#" . $key . "\">" . $thirdnextvalue [helpConstants::SHORT_CLASSNAME] . "</a></font></td>";
            $tables .= "<td height=\"30\"><font color=\"#006600\">" . $thirdnextvalue [helpConstants::DOCCOMMENTS] . "</font></td>";

            $tables .= "</tr>";
        }

        $tables .= "</table>";
        $contents .= $tables;

        // 具体服务信息
        foreach ($services as $value) {

            $key = $value [helpConstants::CLASSNAME];

            $contents .= '<hr/><h1><a name="' . $key . '" href="#" >' . $key . '</a></h1>';
            $contents .= '<h2>' . $value [helpConstants::DOCCOMMENTS] . '</h2>';

            $testfunctionservername = $value [helpConstants::SHORT_CLASSNAME];

            $service_table = "<table border=\"1\">";
            $service_table .= "<tr>";
            $service_table .= "<th>函数名称</th>";
            $service_table .= "<th>函数描述</th>";
            $service_table .= "<th>返回值描述</th>";
            $service_table .= "<th>是否是测试函数</th>";
            $service_table .= "</tr>";

            $functionservice = $value [helpConstants::SERVICES] [helpConstants::SERVICES];
            foreach ($functionservice as $functionname => $value) {

                $params = implode(",", $value [helpConstants::FUNCTIONPARAMS]);

                // functionsDump ( $_SERVER );
                $testfunctionname = $testfunctionservername . '.' . $functionname;

                $postparams = "";
                foreach ($value [helpConstants::FUNCTIONPARAMS] as $value1) {
                    $postparams .= $value1 . "%3d%3f%26";
                }

                if (strlen($postparams) > 0) {
                    $postparams = substr($postparams, 0, strlen($postparams) - 3);
                    // functionsDump ( $postparams );
                }

                // htmlspecialchars_decode($string)
                // htmlspecialchars($string)

                $service_functiondata = new BaseCallableFunctionData ();
                $service_functiondata->fromArray($value [helpConstants::SERVICE_DATA]);

                $hrefparams = 'functionname=' . $testfunctionname;
                $hrefparams .= '&params=' . ($postparams);
                $service_table .= "<tr >";
                $service_table .= '<td><font color="#FF0000"><a target="_blank" href="http://' . $_SERVER ['HTTP_HOST'] . '/test.php?' . $hrefparams . '">' . $functionname . " (" . $params . ")</a></font></td>";
                $service_table .= "<td><font color=\"#006600\">" . str_replace("\n", "<br>", $value [helpConstants::DOCCOMMENTS]) . "</font></td>";
                $service_table .= "<td><font color=\"#006600\">" . $value [helpConstants::RETCODECOMMENTS] . "</font></td>";
                if ($service_functiondata->get_isDebugFunction()) {
                    $service_table .= "<td><font color=\"#FF0000\">是</font></td>";
                } else {
                    $service_table .= "<td><font color=\"#006600\">否</font></td>";
                }
                $service_table .= "</tr>";
            }

            $service_table .= "</table>";
            $service_table .= '<hr/>';
            $service_table .= "<br><br><br><br><br><br><br>";

            $contents .= $service_table;
        }

        $contents .= "</body>\n";
        $contents .= "</html>";

        fputs($handle, $contents);
        fclose($handle);

        $a = '<a href="caches/' . $shorthtmlfilename . '" target="_blank">说明文档 移步这里</a><br>';
        echo($a);
    }
}