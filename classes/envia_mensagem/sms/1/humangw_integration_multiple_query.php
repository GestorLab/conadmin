<?php
ini_set('display_errors', "on");
ini_set('error_reporting', E_ALL & ~E_NOTICE);
include_once 'human_gateway_client_api/HumanClientMain.php';

// Exemplo para testar a lista
$msg_list = array();
$msg_list[] = "001";
$msg_list[] = "002";
$msg_list[] = "003";
$msg_list[] = "004";
$msg_list[] = "005";
$msg_list[] = "006";
$msg_list[] = "007";
$msg_list[] = "008";
$msg_list[] = "009";
$msg_list[] = "010";

// Faz a chamada
$humanMultipleSend = new HumanMultipleSend("conta", "senha");

$response = $humanMultipleSend->queryMultipleStatus($msg_list);

foreach ($response as $resp) {
	echo $resp->getCode() . " - " . $resp->getMessage() . "<br />";
}