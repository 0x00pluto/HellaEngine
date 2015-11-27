<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/20
 * Time: 下午5:31
 */

$app = new \app\Application(
    realpath(__DIR__ . '/../')
);

$app->initialize();


return $app;