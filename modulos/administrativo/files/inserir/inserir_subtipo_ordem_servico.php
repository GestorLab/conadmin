<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inser��o de SubTipoOrdemServico
		$sql	=	"select (max(IdSubTipoOrdemServico)+1) IdSubTipoOrdemServico from SubTipoOrdemServico where IdLoja = $local_IdLoja and IdTipoOrdemServico = $local_IdTipoOrdemServico";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdSubTipoOrdemServico]!=NULL){
			$local_IdSubTipoOrdemServico	=	$lin[IdSubTipoOrdemServico];
		}else{
			$local_IdSubTipoOrdemServico	=	1;
		}
	
		$sql	=	"
				INSERT INTO SubTipoOrdemServico SET
					IdLoja							= $local_IdLoja,
					IdTipoOrdemServico				='$local_IdTipoOrdemServico',
					IdSubTipoOrdemServico			='$local_IdSubTipoOrdemServico',
					DescricaoSubTipoOrdemServico	='$local_DescricaoSubTipoOrdemServico',
					Cor								='$local_Cor', 
					LoginCriacao					='$local_Login', 
					DataCriacao						=concat(curdate(),' ',curtime())";
					
			// Executa a Sql de Inser��o de TipoOrdemServico
		if(mysql_query($sql,$con) == true){						
			$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inser��o Positiva
		}else{
			// Muda a a��o para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inser��o Negativa
		}
	}
?>
