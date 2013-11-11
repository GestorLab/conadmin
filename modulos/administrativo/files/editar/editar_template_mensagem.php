<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"U")){
		$local_Erro = 2;
	} else{
	 	$sql = "update TemplateMensagem set 
					DescricaoTemplate	= \"$local_DescricaoTemplate\",
					Estrutura			= '$local_Estrutura',
					LoginAlteracao		= '$local_Login', 
					DataAlteracao		= (concat(curdate(),' ',curtime()))
				where
					IdLoja = '$local_IdLoja' and
					IdTemplate = '$local_IdTemplate';";
		if(@mysql_query($sql, $con)){
			$local_Erro = 4;			# MENSAGEM DE ALTERAÇÃO POSITIVA
		} else{
			$local_Erro = 5;			# MENSAGEM DE ALTERAÇÃO NEGATIVA
		}
	}
?>
