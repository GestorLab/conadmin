<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inser��o de ServicoGrupo
		$sql	=	"select (max(IdServicoGrupo)+1) IdServicoGrupo from ServicoGrupo where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdServicoGrupo]!=NULL){
			$local_IdServicoGrupo	=	$lin[IdServicoGrupo];
		}else{
			$local_IdServicoGrupo	=	1;
		}
		$sql	=	"
				INSERT INTO ServicoGrupo SET
					IdLoja					= $local_IdLoja,
					IdServicoGrupo			='$local_IdServicoGrupo',
					DescricaoServicoGrupo	='$local_DescricaoServicoGrupo',
					LoginCriacao			='$local_Login', 
					DataCriacao				=(concat(curdate(),' ',curtime())),
					LoginAlteracao			= NULL,
					DataAlteracao			= NULL";
					
			// Executa a Sql de Inser��o de ServicoGrupo
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
