<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inser��o de GrupoPessoa
		$sql	=	"select (max(IdGrupoPessoa)+1) IdGrupoPessoa from GrupoPessoa where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdGrupoPessoa]!=NULL){
			$local_IdGrupoPessoa	=	$lin[IdGrupoPessoa];
		}else{
			$local_IdGrupoPessoa	=	1;
		}
		$sql	=	"
				INSERT INTO GrupoPessoa SET 
					IdLoja					= $local_IdLoja,
					IdGrupoPessoa			= $local_IdGrupoPessoa, 
					DescricaoGrupoPessoa	= '$local_DescricaoGrupoPessoa',
					DataCriacao				= (concat(curdate(),' ',curtime())),
					LoginCriacao			= '$local_Login';";
					
		// Executa a Sql de Inser��o de Pais
		if(mysql_query($sql,$con) == true){						
			$local_Acao = 'alterar';	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inser��o Positiva
		}else{
			// Muda a a��o para Inserir
			$local_Acao = 'inserir';
			$local_Erro = 1;			// Mensagem de Inser��o Negativa
		}
	}
?>
