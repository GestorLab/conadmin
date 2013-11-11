<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de ProdutoTipoVigencia
		$sql	=	"select (max(IdProdutoTipoVigencia)+1) IdProdutoTipoVigencia from ProdutoTipoVigencia where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdProdutoTipoVigencia]!=NULL){
			$local_IdProdutoTipoVigencia	=	$lin[IdProdutoTipoVigencia];
		}else{
			$local_IdProdutoTipoVigencia	=	1;
		}
		$sql	=	"
				INSERT INTO ProdutoTipoVigencia SET
					IdLoja							= $local_IdLoja,
					IdProdutoTipoVigencia			='$local_IdProdutoTipoVigencia',
					DescricaoProdutoTipoVigencia	='$local_DescricaoProdutoTipoVigencia',
					LoginCriacao					='$local_Login', 
					DataCriacao						=(concat(curdate(),' ',curtime())),
					LoginAlteracao					= NULL,
					DataAlteracao					= NULL";
					
			// Executa a Sql de Inserção de ProdutoTipoVigencia
		if(mysql_query($sql,$con) == true){						
			$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		}else{
			// Muda a ação para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
	}
?>
