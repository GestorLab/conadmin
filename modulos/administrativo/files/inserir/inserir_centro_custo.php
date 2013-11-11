<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inserção de Unidade
		$sql	=	"select (max(IdCentroCusto)+1) IdCentroCusto from CentroCusto where IdLoja=$local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdCentroCusto]!=NULL){
			$local_IdCentroCusto	=	$lin[IdCentroCusto];
		}else{
			$local_IdCentroCusto	=	1;
		}
		$sql	=	"
				INSERT INTO CentroCusto SET 
					IdLoja					= $local_IdLoja, 
					IdCentroCusto			= $local_IdCentroCusto, 
					DescricaoCentroCusto	= '$local_DescricaoCentroCusto', 
					IdStatus				= $local_IdStatus, 
					LoginCriacao			='$local_Login', 
					DataCriacao				=(concat(curdate(),' ',curtime())),
					DataAlteracao			= NULL,
					LoginAlteracao			= NULL;";
					
			// Executa a Sql de Inserção de Unidade
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
