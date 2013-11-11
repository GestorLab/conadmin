<?php
ini_set('display_errors', "on");
ini_set('error_reporting', E_ALL & ~E_NOTICE);
include_once 'human_gateway_client_api/HumanClientMain.php';

// Exemplo para testar a lista no lay-out C
$msg_list  = "550092167288;teste0;004"."\n";
$msg_list .= "550081262695;teste1;005"."\n";
$msg_list .= "550081337773;teste2;006"."\n";
$msg_list .= "550096025425;teste3;007"."\n";


// Faz a chamada usando lay-out C
$humanMultipleSend = new HumanMultipleSend("conta", "senha");

$response = $humanMultipleSend->sendMultipleList(HumanMultipleSend::TYPE_C, $msg_list);

foreach ($response as $resp) {
	echo $resp->getCode() . " - " . $resp->getMessage() . "<br />";
}
