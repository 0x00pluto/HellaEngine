<?php

$loader = require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../hellaEngine/autoload.php";

if ($loader instanceof \Composer\Autoload\ClassLoader) {
    //如果这个日后放到Composer中 则不用这么麻烦
    $loader->add('hellaEngine', __DIR__ . "/../");

    $loader->add('app', __DIR__ . "/../");

}




