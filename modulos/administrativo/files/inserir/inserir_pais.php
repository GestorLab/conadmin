<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inser��o de pais
		$sql	=	"select (max(IdPais)+1) IdPais from Pais";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdPais]!=NULL){
			$local_IdPais	=	$lin[IdPais];
		}else{
			$local_IdPais	=	1;
		}
		$sql	=	"
				INSERT INTO Pais SET 
					IdPais				= $local_IdPais, 
					NomePais			= '$local_Pais';";
					
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
