<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"U")){
		$local_Erro = 2;
	} else{
		if($local_LimiteEnvioDiario == ''){
			$local_LimiteEnvioDiario = "NULL";
		} else{
			$local_LimiteEnvioDiario = "'$local_LimiteEnvioDiario'";
		}
		$local_Login	= $_SESSION["Login"];
		$local_Conteudo = str_replace('"',"'",$local_Conteudo);//Leonardo - 22-01-13 10:06/converte aspas duplas em aspas simples
	 	$SMS = "";
		if($local_IdTemplate == 4){
			$SMS = "IdContaSMS = '$local_IdContaEmail',";
		}
		if($local_IdContaEmail == 0 && $local_Login != 'root'){
			$local_Erro = 190;
		}else{			
			$sql = "update TipoMensagem set 
						IdTemplate			= '$local_IdTemplate',
						IdContaEmail		= '$local_IdContaEmail',
						$SMS
						LimiteEnvioDiario	= $local_LimiteEnvioDiario,
						DelayDisparo		= '$local_Delay',
						Titulo				= \"$local_Titulo\",
						Assunto				= \"$local_Assunto\",
						Conteudo			= \"$local_Conteudo\",
						Assinatura			= \"$local_Assinatura\",
						IdStatus			= '$local_IdStatus',
						LoginAlteracao		= '$local_Login', 
						DataAlteracao		= (concat(curdate(),' ',curtime()))
					where
						IdLoja = '$local_IdLoja' and
						IdTipoMensagem = '$local_IdTipoMensagem';";

			if(@mysql_query($sql, $con)){
				echo mysql_error();
				$local_Erro = 4;		# MENSAGEM DE ALTERAÇÃO POSITIVA
			} else{
				echo mysql_error();
				$local_Erro = 5;		# MENSAGEM DE ALTERAÇÃO NEGATIVA
			}
		}
	}
?>
