<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inser��o de Estado
		$sql	=	"select (max(IdEstado)+1) IdEstado from Estado where IdPais=$local_IdPais";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdEstado]!=NULL){
			$local_IdEstado	=	$lin[IdEstado];
		}else{
			$local_IdEstado	=	1;
		}
		$sql	=	"
				INSERT INTO Estado SET 
					IdPais				= $local_IdPais, 
					IdEstado			= $local_IdEstado, 
					NomeEstado			= '$local_Estado',
					SiglaEstado			= '$local_SiglaEstado';";
					
		// Executa a Sql de Inser��o de Estado
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
