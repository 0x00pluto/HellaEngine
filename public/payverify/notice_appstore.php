<?php
use constants\constants_platformtype;
use payverify\notice\payverify_notice_appledata;
use payverify\notice\payverify_notice_gateway;
require_once ("../../include/bootstrap/global.php");

if (! isset ( $_POST ['orderid'] )) {
	return;
}
if (! isset ( $_POST ['receipt'] )) {
	return;
}

$data = new payverify_notice_appledata ();

$data->fromArray ( $_POST );
// functionsDump ( $data );
if ($data->get_platformtype () != constants_platformtype::APPSTORE) {
	return;
}

payverify_notice_gateway::getInstance ()->call ( $data );
