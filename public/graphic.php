<?php
header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); // Date in the past

header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" ); // always modified

header ( "Cache-Control: no-cache, must-revalidate" ); // HTTP/1.1

header ( "Pragma: no-cache" ); // HTTP/1.0

header ( "Content-type: image/gif" );
$filename = '/tmp/domain.txt';
$somecontent = "digraph G\n
	{
		edge [fontname=\"Simsun\"];
		node [shape=box,fontname=\"Simsun\"];
		mission[label=\"任务服务 @author zhipeng * */\nsmission\",color=green,style=filled];
		missiongetinfo[label=\"function getinfo()\n/**\n* 获取任务基础信息\n*\n* @return CommonUtilReturnVar\n*/\nfunction getinfo()\"];
		missiontest;
		missioncompleteachievement;
		edge[color=red];

		D [label=\"100ti\nm\nes\"];
		A->B->C->D;
		D->B->E;
		E->F [style=bold,label=\"100times\"];
		mission->missiongetinfo;
		mission->missiontest;
		mission->missioncompleteachievement;
}
	";
// $somecontent = utf8_decode($somecontent);

// $somecontent = "\xEF\xBB\xBF" . ($somecontent);
// $somecontent = mb_convert_encoding($somecontent, 'UTF-8', 'OLD-ENCODING');

if (! $handle = fopen ( $filename, 'w' )) {
	echo "cannot open $filename";
	exit ();
}
if (fwrite ( $handle, $somecontent ) === FALSE) {
	echo "cannot write to $filename";
	exit ();
}
fclose ( $handle );

passthru ( "dot -Tpng $filename" );