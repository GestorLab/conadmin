<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de GrupoProduto
		$sql	=	"select (max(IdGrupoProduto)+1) IdGrupoProduto from GrupoProduto where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdGrupoProduto]!=NULL){
			$local_IdGrupoProduto	=	$lin[IdGrupoProduto];
		}else{
			$local_IdGrupoProduto	=	1;
		}
		$sql	=	"
				INSERT INTO GrupoProduto SET
					IdLoja					= $local_IdLoja,
					IdGrupoProduto			='$local_IdGrupoProduto',
					DescricaoGrupoProduto	='$local_DescricaoGrupoProduto',
					LoginCriacao			='$local_Login', 
					DataCriacao				=(concat(curdate(),' ',curtime())),
					LoginAlteracao			= NULL,
					DataAlteracao			= NULL";
					
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
