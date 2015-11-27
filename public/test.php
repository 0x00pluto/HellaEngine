<?php
use utilphp\util;

session_start();
?>
<head>
    <title><?php
        $functionName = "";
        if (isset ($_POST ["functionname"])) {
            $functionName = $_POST ["functionname"];
        } elseif (isset ($_GET ["functionname"])) {
            $functionName = $_GET ["functionname"];
        } elseif (isset ($_COOKIE ["functionname"])) {
            $functionName = $_COOKIE ["functionname"];
        }

        $functionName = trim($functionName);
        $host = $_SERVER ['HTTP_HOST'];
        $host = explode(".", $host) [0];

        echo $host . ':' . $functionName ?>
    </title>
</head>
<body>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
      method="post">
    <table>
        <tr>
            <td>functionName:</td>
            <td width='900'>
                <input type="text" name="functionname"
                       style="width: 100%"
                       value="<?php
                       $functionName = "";
                       if (isset ($_POST ["functionname"])) {
                           $functionName = $_POST ["functionname"];
                       } elseif (isset ($_GET ["functionname"])) {
                           $functionName = $_GET ["functionname"];
                       } elseif (isset ($_COOKIE ["functionname"])) {
                           $functionName = $_COOKIE ["functionname"];
                       }

                       $functionName = trim($functionName);
                       echo $functionName ?>"/>
                <br>
            </td>
        </tr>
        <tr>
            <td>verify:</td>
            <td><input type="text" name="verify" style="width: 100%"
                       value="<?php
                       $verify = "";
                       if (isset($_POST ["verify"])) {
                           $verify = $_POST ["verify"];
                       } elseif (isset ($_GET ["verify"])) {
                           $verify = $_GET ["verify"];
                       } elseif (isset ($_COOKIE ["verify"])) {
                           $verify = $_COOKIE ["verify"];
                       }
                       $verify = trim($verify);
                       echo $verify ?>"/><br><?php

                ?></td>
        </tr>
        <tr>
            <td>params:</td>
            <td><input type="text" name="params" style="width: 100%"
                       value="<?php
                       $params = "";
                       if (isset ($_POST ["params"])) {
                           $params = $_POST ["params"];
                       } elseif (isset ($_GET ["params"])) {
                           $params = $_GET ["params"];
                       } elseif (isset ($_COOKIE ["params"])) {
                           $params = $_COOKIE ["params"];
                       }
                       $params = trim($params);
                       echo $params ?>"
                    />
            </td>
        </tr>
        <tr>
            <td><input type="submit" value="提交"/></td>
            <td>username=test1003&password=1111211&thirdpartytype=1</td>
        </tr>
    </table>
</form>
</body>

<?php

// $_SESSION[]
/**
 * 输出变量
 *
 * @param void $varVal
 *            变量值
 * @param bool $isExit
 *            是否输出变量之后就结束程序（TRUE:是 FALSE:否）
 */
function _dump($varVal, $isExit = FALSE)
{
    ob_start();
    $debugInfo = debug_backtrace();
    $debugInfo = $debugInfo [0];
    echo $debugInfo ["file"] . ":" . $debugInfo ['line'] . '<br>';
    var_dump($varVal);
    $varVal = ob_get_clean();
    $varVal = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $varVal);
    echo '<pre>' . $varVal . '</pre>';
    $isExit && exit ();
}

function __registerError()
{
    $ar = array(
        E_ERROR => 'error',
        E_WARNING => 'warning',
        E_PARSE => 'prase',
        E_NOTICE => 'notice'
    );
    register_shutdown_function(function () use ($ar) {
        $ers = error_get_last();
        if ($ers ['type'] != 8 && $ers ['type']) {
            $er = $ar [$ers ['type']] . $ers ['type'] . ': ' . ' ' . $ers ['message'] . ' => ' . $ers ['file'] . ' line:' . $ers ['line'] . ' ' . date('Y-m-d H:i:s') . "n<br>";
            // error_log ( $er, 3, '/tmp/php_error.log' );
            // var_dump($er);

            echo($er);
        }
    });
    set_error_handler(function ($a, $b, $c, $d) use ($ar) {
        if ($a != 8 && $a != 8192 && $a) {
            $er = $ar [$a] . $a . ': ' . $b . ' => ' . $c . ' line:' . $d . ' ' . date('Y-m-d H:i:s') . "n<br>";
            // error_log ( $er, 3, '/tmp/php_error.log' );
            // _dump ( $ar );
            echo($er);
        }
    }, E_ALL ^ E_NOTICE);
}

if (array_key_exists("functionname", $_POST)) {
    $functionName = $_POST ["functionname"];
}

$verify = "";
if (isset ($_POST ["verify"])) {
    $verify = $_POST ["verify"];
} elseif (isset ($_GET ["verify"])) {
    $verify = $_GET ["verify"];
} elseif (isset ($_COOKIE ["verify"])) {
    $verify = $_COOKIE ["verify"];
}
$functionparams = "";
if (array_key_exists("params", $_POST)) {
    $functionparams = $_POST ["params"];
}
// 如果需要返回,则从_GET中获取函数名
if (isset ($_GET ['backurl'])) {
    $functionName = $_GET ["functionname"];
    // $verify = "";
    $functionparams = "";
}
if ($functionName == null) {
    return;
}

setcookie('functionname', $functionName, time() + 60 * 60 * 24);

function __buildmessage($cmd, $verify, $postparams)
{
    $MSG_TYPE = array(
        "MSG_DATA" => 0,
        "MSG_GZIP" => 1,
        "MSG_ERROR" => 2
    );
    // 构建消息头
    $header = array();
    $header ['verMajor'] = 1;
    $header ['verMin'] = 1;
    $header ['msgType'] = $MSG_TYPE ['MSG_DATA'];

    // 消息体
    $data = array();
    $message = array(
        'header' => &$header,
        'data' => &$data
    );
    // 'verify' => $_POST ['verify']

    $data ['cmd'] = $cmd; // $_POST ["functionname"];

    $data ['cmdid'] = 1;
    $data ['verify'] = $verify; // $_POST ['verify'];

    $recvparams = explode("&", $postparams); // $_POST ["params"] );
    $params = array();
    foreach ($recvparams as $value) {
        $pos = strpos($value, "=");
        $p = explode("=", $value);

        $pkey = substr($value, 0, $pos);
        $pvalue = substr($value, $pos + 1);

        // _dump ( $pkey );
        // _dump ( $pvalue );

        $params [trim($pkey)] = trim($pvalue);
    }
    $data ['params'] = $params;

    return $message;
}

// _dump($data);
// $datajson = json_encode ( $data );
// $datajsonlen = strlen ( $datajson );
// 判断是否需要压缩
// if ($datajsonlen > 100) {
// $data = base64_encode ( gzcompress ( $datajson ) );
// $header ['msgType'] = $MSG_TYPE ['MSG_GZIP'];
// } else {
// $data = base64_encode ( $datajson );
// }
// exit();

$messages = array();
$message = __buildmessage($functionName, $verify, $functionparams);
$_SESSION ['verify'] = $verify;

setcookie('verify', $verify, time() + 60 * 60 * 24);

array_push($messages, $message);

__registerError();
include(__DIR__ . "/../bootstrap/autoload.php");
$gateway = require(__DIR__ . "/../bootstrap/app.php");

$jsonMsg = \hellaEngine\Supports\Common\Util\CommonUtilMessage::encodeMessage($messages);
C(\hellaEngine\Configure\Constants::DEBUG, true);
C(\hellaEngine\Configure\Constants::PHP_PROFILE, true);
C(\hellaEngine\Configure\Constants::DEBUG_DB, true);
C(\hellaEngine\Configure\Constants::DUMP_ENABLE, true);

echo(">>>>>>>>>>>>>>>>>>>>>> begin debug info >>>>>>>>>>>>>>>>>>>>>><br>");
// 模拟数据提交
$_POST ["data"] = $jsonMsg;


$output = $gateway->processMessage($jsonMsg);

if (!isset ($_GET ['backurl'])) {

    echo("<br><<<<<<<<<<<<<<<<<<<<<< end debug info   <<<<<<<<<<<<<<<<<<<<<<<br><br>");
    echo(">>>>>>>>>>>>>>>>>>>>>> function return >>>>>>>>>>>>>>>>>>>>>><br>");
    // $output = json_decode ( gzuncompress ( base64_decode ( $data ) ) );
    $returnmessages = \hellaEngine\Supports\Common\Util\CommonUtilMessage::decodeMessage($output);

    // _dump ( $returnmessages );
    foreach ($returnmessages as $value) {
        // _dump ( $value );
        $command = \hellaEngine\Supports\Common\Util\CommonUtilMessage::Message_getCommand($value);
        // _dump ( $command );
        util::var_dump($command, false, -1);
    }

    // _dump ( $_SERVER );

    echo("<<<<<<<<<<<<<<<<<<<<<< function return <<<<<<<<<<<<<<<<<<<<<<<br>");
    // echo (">>>>return messagelenSRC:" . strlen ( $uncompressString ) . "<br>");
    echo(">>>>return messagelenQQ:" . strlen($output) . "<br>");
    echo(">>>>return message:" . $output . "<br>");
    if (C(configure_constants::DEBUG_DB)) {
        echo(">>>>>>>>>>>>>>>>>>>>>>>dbinfo>>>>>>>>>>>>>>>>>>>>>>><br>");
        if (isset($GLOBALS [configure_constants::DEBUG_DB_DIRTY_KEY])) {
            $debugdbarray = $GLOBALS [configure_constants::DEBUG_DB_DIRTY_KEY];
            if (!is_null($debugdbarray)) {
                // _dump ( $debugdbarray );

                util::var_dump($debugdbarray, false, -1);
            }
        }
    }
} else {
    echo $_GET ['backurl'];
    $url = "Location:" . $_GET ['backurl'];
    header($url);
    exit ();
    // echo "here";
}

?>
