<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inser��o de Estoque
		$sql	=	"select (max(IdEstoque)+1) IdEstoque from Estoque where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdEstoque]!=NULL){
			$local_IdEstoque	=	$lin[IdEstoque];
		}else{
			$local_IdEstoque	=	1;
		}
		$sql	=	"
				INSERT INTO Estoque SET
					IdLoja					='$local_IdLoja',
					IdEstoque				='$local_IdEstoque',
					DescricaoEstoque		='$local_DescricaoEstoque',
					LoginCriacao			='$local_Login', 
					DataCriacao				=(concat(curdate(),' ',curtime())),
					LoginAlteracao			= NULL,
					DataAlteracao			= NULL";
					
		// Executa a Sql de Inser��o de Estoque
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
