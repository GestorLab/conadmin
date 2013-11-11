<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"I")){
		$local_Erro = 2;
	} else{
		# SQL PARA OBTER O PROXIMO ID
		$sql = "select 
					(max(MonitorPorta.IdMonitor) + 1) IdMonitor 
				from 
					MonitorPorta 
				where 
					MonitorPorta.IdLoja = '$local_IdLoja';";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdMonitor] != NULL){
			$local_IdMonitor = $lin[IdMonitor];
		} else{
			$local_IdMonitor = 1;
		}
		
		if($local_Timeout == "")
			$local_Timeout = "NULL";
		
	 	$sql = "insert into MonitorPorta set
					IdLoja				= '$local_IdLoja', 
					IdMonitor			= '$local_IdMonitor', 
					DescricaoMonitor	= '$local_DescricaoMonitor', 
					IdStatus			= '$local_IdStatus', 
					HostAddress			= '$local_HostAddress', 
					Porta				= '$local_Porta',
					Timeout				= $local_Timeout,
					Latitude			= '$local_Latitude',
					Longitude			= '$local_Longitude',
					LoginCriacao		= '$local_Login', 
					DataCriacao			= (concat(curdate(),' ',curtime()));";
		
		if(@mysql_query($sql,$con)){
			$local_Acao = 'alterar';
			$local_Erro = 3;			# MENSAGEM DE INSERวรO POSITIVA
		} else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			# MENSAGEM DE INSERวรO NEGATIVA
		}
	}
?>