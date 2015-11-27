<?php
require_once ("../../include/bootstrap/global.php");

use payverify\check\payverify_check_requestdata;
use payverify\check\payverify_check_gateway;

// 限制ip访问
// 客户端校验数据

$data = new payverify_check_requestdata ();
$data->fromArray ( $_POST );
$response = payverify_check_gateway::getInstance ()->call ( $data );
$array = $response->toArray ();
$returnjson = json_encode ( $array );
echo $returnjson;
