<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"U")){
		$local_Erro = 2;
	} else{
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
		
		$sql = "update ProtocoloTipo set
					DescricaoProtocoloTipo	= '$local_DescricaoProtocoloTipo',
					AberturaCDA				= $local_AberturaCDA,
					IdGrupoUsuarioAbertura	= $local_IdGrupoUsuarioAbertura,
					LoginAbertura			= $local_LoginAbertura,
					IdStatus				= $local_IdStatus,
					LoginAlteracao			= '$local_Login', 
					DataAlteracao			= (concat(curdate(),' ',curtime()))
				where
					IdLoja = '$local_IdLoja' and
					IdProtocoloTipo = '$local_IdProtocoloTipo';";
		if(mysql_query($sql,$con)){
			$local_Erro = 4;			// Mensagem de Inserчуo Positiva
		} else{
			$local_Erro = 5;			// Mensagem de Inserчуo Negativa
		}
	}
?>