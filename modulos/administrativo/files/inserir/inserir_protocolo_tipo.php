<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"I")){
		$local_Erro = 2;
	} else{
		$sql = "select (max(IdProtocoloTipo) + 1) IdProtocoloTipo from ProtocoloTipo;";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdProtocoloTipo] != NULL){ 
			$local_IdProtocoloTipo = $lin[IdProtocoloTipo];
		} else{
			$local_IdProtocoloTipo = 10001;
		}
		
		if($local_IdGrupoUsuarioAbertura == ''){
			$local_IdGrupoUsuarioAbertura = "NULL";
		} else{
			$local_IdGrupoUsuarioAbertura = "'$local_IdGrupoUsuarioAbertura'";
		}
		
		if($local_LoginAbertura == ''){
			$local_LoginAbertura = "NULL";
		} else{
			$local_LoginAbertura = "'$local_LoginAbertura'";
		}
		
		$sql = "insert into ProtocoloTipo set
					IdLoja					= '$local_IdLoja',
					IdProtocoloTipo			= $local_IdProtocoloTipo,
					DescricaoProtocoloTipo	= '$local_DescricaoProtocoloTipo',
					AberturaCDA				= $local_AberturaCDA,
					IdGrupoUsuarioAbertura	= $local_IdGrupoUsuarioAbertura,
					LoginAbertura			= $local_LoginAbertura,
					IdStatus				= $local_IdStatus,
					LoginCriacao			= '$local_Login',
					DataCriacao				= (concat(curdate(),' ',curtime()));";
		if(mysql_query($sql,$con)){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserчуo Positiva
		} else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserчуo Negativa
		}
	}
?>