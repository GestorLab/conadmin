<?php

include_once 'human_gateway_client_api/HumanClientMain.php';


// Faz a chamada usando lay-out C

/***************************************************************************************************************/

$arquivo = "C:/arquivo.csv";
$humanMultipleSend = new HumanMultipleSend("conta", "senha");
$response = $humanMultipleSend->sendMultipleFileCSV(HumanMultipleSend::TYPE_C, $arquivo);

foreach ($response as $resp) {
	echo $resp->getCode() . " - " . $resp->getMessage() . "<br />";
}