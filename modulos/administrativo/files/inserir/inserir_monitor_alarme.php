<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"I")){
		$local_Erro = 2;
	} else{
	 	$sql = "insert into MonitorPortaAlarme set
					IdLoja					= '$local_IdLoja', 
					IdMonitor				= '$local_IdMonitor', 
					IdTipoMensagem			= '17', 
					IdStatus				= '$local_IdStatus', 
					Mensagem				= '$local_Mensagem', 
					QtdTentativas			= '$local_QtdTentativas', 
					IntervaloTentativa		= '$local_IntervaloTentativa', 
					DestinatarioMensagem	= '$local_DestinatarioMensagem';";
		
		if(@mysql_query($sql,$con)){
			$local_Acao = 'alterar';
			$local_Erro = 3;			# MENSAGEM DE INSERวรO POSITIVA
		} else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			# MENSAGEM DE INSERวรO NEGATIVA
		}
	}
?>