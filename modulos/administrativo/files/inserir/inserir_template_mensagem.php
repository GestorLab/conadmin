<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"I")){
		$local_Erro = 2;
	} else{
		# SQL PARA OBTER O PROXIMO ID
		$sql = "select 
					(max(IdTemplate) + 1) IdTemplate 
				from 
					TemplateMensagem 
				where 
					TemplateMensagem.IdLoja = '$local_IdLoja';";
		$res = mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdTemplate] != NULL){
			$local_IdTemplate = $lin[IdTemplate];
		} else{
			$local_IdTemplate = 1;
		}
		
	 	$sql = "insert into TemplateMensagem set
					IdLoja				= '$local_IdLoja', 
					IdTemplate			= '$local_IdTemplate',
					DescricaoTemplate	= '$local_DescricaoTemplate',
					Estrutura			= '$local_Estrutura',
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