<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"U")){
		$local_Erro = 2;
	} else{
		if($local_Timeout == "")
			$local_Timeout = "NULL";
		
	 	$sql = "update MonitorPorta set 
					DescricaoMonitor	= '$local_DescricaoMonitor', 
					IdStatus			= '$local_IdStatus', 
					HostAddress			= '$local_HostAddress', 
					Porta				= '$local_Porta',
					Timeout				= $local_Timeout,
					Latitude			= '$local_Latitude',
					Longitude			= '$local_Longitude',
					LoginAlteracao		= '$local_Login', 
					DataAlteracao		= (concat(curdate(),' ',curtime()))
				where
					IdLoja = '$local_IdLoja' and
					IdMonitor = '$local_IdMonitor';";
		
		if(@mysql_query($sql, $con)){
			$local_Erro = 4;			# MENSAGEM DE ALTERAวรO POSITIVA
		} else{
			$local_Erro = 5;			# MENSAGEM DE ALTERAวรO NEGATIVA
		}
	}
?>