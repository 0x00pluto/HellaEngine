<?php
require __DIR__ . "/../bootstrap/autoload.php";


$returnData = "f";
if (isset ($_POST ["data"])) {

    $gateway = require __DIR__ . "/../bootstrap/app.php";
    $returnData = $gateway->processMessage($_POST ["data"]);
}

echo $returnData;
