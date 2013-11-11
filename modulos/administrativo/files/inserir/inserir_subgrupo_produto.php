<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de SubGrupoProduto
		$sql	=	"select (max(IdSubGrupoProduto)+1) IdSubGrupoProduto from SubGrupoProduto where IdLoja = $local_IdLoja and IdGrupoProduto = $local_IdGrupoProduto";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdSubGrupoProduto]!=NULL){
			$local_IdSubGrupoProduto	=	$lin[IdSubGrupoProduto];
		}else{
			$local_IdSubGrupoProduto	=	1;
		}
		$sql	=	"
				INSERT INTO SubGrupoProduto SET
					IdLoja						= $local_IdLoja,
					IdGrupoProduto				='$local_IdGrupoProduto',
					IdSubGrupoProduto			='$local_IdSubGrupoProduto',
					DescricaoSubGrupoProduto	='$local_DescricaoSubGrupoProduto',
					LoginCriacao				='$local_Login', 
					DataCriacao					=(concat(curdate(),' ',curtime())),
					LoginAlteracao				= NULL,
					DataAlteracao				= NULL";
					
			// Executa a Sql de Inserção de GrupoProduto
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
