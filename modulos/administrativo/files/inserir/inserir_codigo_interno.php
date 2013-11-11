<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de Codigo Interno
		$sql	=	"select (max(IdCodigoInterno)+1) IdCodigoInterno from CodigoInterno where IdLoja = $local_IdLoja and IdGrupoCodigoInterno=$local_IdGrupoCodigoInterno";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdCodigoInterno]!=NULL){
			$local_IdCodigoInterno	=	$lin[IdCodigoInterno];
		}else{
			$local_IdCodigoInterno	=	1;
		}
		$sql	=	"
				INSERT INTO CodigoInterno SET 
					IdLoja						= $local_IdLoja,
					IdGrupoCodigoInterno		= $local_IdGrupoCodigoInterno, 
					IdCodigoInterno				= $local_IdCodigoInterno, 
					DescricaoCodigoInterno		= '$local_DescricaoCodigoInterno', 
					ValorCodigoInterno			= '$local_ValorCodigoInterno',
					DataCriacao					= (concat(curdate(),' ',curtime())),
					LoginCriacao				= '$local_Login',
					DataAlteracao				= NULL,
					LoginAlteracao				= NULL;";
					
		// Executa a Sql de Inserção de Codigo Interno
		if(mysql_query($sql,$con) == true){						
			$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 1;			// Mensagem de Inserção Negativa
		}
	}
?>
