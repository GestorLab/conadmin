<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inser��o de TabelaPreco
		$sql	=	"select (max(IdTabelaPreco)+1) IdTabelaPreco from TabelaPreco where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdTabelaPreco]!=NULL){
			$local_IdTabelaPreco	=	$lin[IdTabelaPreco];
		}else{
			$local_IdTabelaPreco	=	1;
		}
		$sql	=	"
				INSERT INTO TabelaPreco SET
					IdLoja					='$local_IdLoja',
					IdTabelaPreco			='$local_IdTabelaPreco',
					DescricaoTabelaPreco	='$local_DescricaoTabelaPreco',
					LoginCriacao			='$local_Login', 
					DataCriacao				=(concat(curdate(),' ',curtime())),
					LoginAlteracao			= NULL,
					DataAlteracao			= NULL";
					
		// Executa a Sql de Inser��o de TabelaPreco
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
