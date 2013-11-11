<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"U")){
		$local_Erro = 2;
	} else{
	 	$sql = "update MonitorPortaAlarme set
					Mensagem				= '$local_Mensagem', 
					QtdTentativas			= '$local_QtdTentativas', 
					IntervaloTentativa		= '$local_IntervaloTentativa', 
					DestinatarioMensagem	= '$local_DestinatarioMensagem'
				where
					IdLoja = '$local_IdLoja' and
					IdMonitor = '$local_IdMonitor' and 
					IdTipoMensagem = '17' and 
					IdStatus = '$local_IdStatus';";
		
		if(@mysql_query($sql, $con)){
			$local_Erro = 4;			# MENSAGEM DE ALTERAวรO POSITIVA
		} else{
			$local_Erro = 5;			# MENSAGEM DE ALTERAวรO NEGATIVA
		}
	}
?>