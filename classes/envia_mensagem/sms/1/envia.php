<?
	include_once($Path."classes/envia_mensagem/sms/$linSMS[IdOperadora]/human_gateway_client_api/HumanClientMain.php");

	$humanMultipleSend = new HumanMultipleSend($varsSMS[1],$varsSMS[2]);
	
	$tipo = HumanMultipleSend::TYPE_C;
	
	$msg_list  = $linSMS[Celular].";$linSMS[Conteudo];$IdHistoricoMensagem;";

	$callBack = HumanMultipleSend::CALLBACK_FINAL_STATUS;

	$responses = $humanMultipleSend->sendMultipleList($tipo,$msg_list,$callBack);
	
	foreach($responses as $response) {
		$errorCode = $response->getCode();
		$errorDesc = $response->getMessage();
	}
?>