<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		// Sql de Inser��o de ContratoTipoVigencia
		$sql	=	"select (max(IdContratoTipoVigencia)+1) IdContratoTipoVigencia from ContratoTipoVigencia where IdLoja = $local_IdLoja";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdContratoTipoVigencia]!=NULL){
			$local_IdContratoTipoVigencia	=	$lin[IdContratoTipoVigencia];
		}else{
			$local_IdContratoTipoVigencia	=	1;
		}
		$sql	=	"
				INSERT INTO ContratoTipoVigencia SET
					IdLoja							= $local_IdLoja,
					IdContratoTipoVigencia			='$local_IdContratoTipoVigencia',
					Isento							='$local_Isento',
					DescricaoContratoTipoVigencia	='$local_DescricaoContratoTipoVigencia',
					LoginCriacao					='$local_Login', 
					DataCriacao						=(concat(curdate(),' ',curtime())),
					LoginAlteracao					= NULL,
					DataAlteracao					= NULL";
					
			// Executa a Sql de Inser��o de ContratoTipoVigencia
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
