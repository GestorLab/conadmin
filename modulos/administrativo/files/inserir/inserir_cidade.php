<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de Cidade
		$sql	=	"select (max(IdCidade)+1) IdCidade from Cidade where IdPais=$local_IdPais and IdEstado=$local_IdEstado";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdCidade]!=NULL){
			$local_IdCidade	=	$lin[IdCidade];
		}else{
			$local_IdCidade	=	1;
		}
		$sql	=	"
				INSERT INTO Cidade SET 
					IdPais				= $local_IdPais, 
					IdEstado			= $local_IdEstado, 
					IdCidade			= $local_IdCidade, 
					NomeCidade			= '$local_Cidade';";
					
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
