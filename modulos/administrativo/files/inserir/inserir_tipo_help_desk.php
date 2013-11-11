<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"select (max(IdTipoHelpDesk)+1) IdTipoHelpDesk from HelpDeskTipo;";
		$res	=	@mysql_query($sql,$conCNT);
		$lin	=	@mysql_fetch_array($res);
		
		if($lin[IdTipoHelpDesk]!=NULL){ 
			$local_IdTipo	= $lin[IdTipoHelpDesk];
		}else{
			$local_IdTipo	= 1;
		}
		
		$sql	=	"
					INSERT INTO
						HelpDeskTipo
					SET
						IdTipoHelpDesk			=  $local_IdTipo,
						DescricaoTipoHelpDesk	= '$local_DescricaoTipo',  
						IdStatus				=  $local_IdStatus,
						DataCriacao				=  (concat(curdate(),' ',curtime())),
						LoginCriacao			= '$local_Login';
		";
		if(mysql_query($sql,$conCNT) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserção Positiva
		} else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserção Negativa
		}
	}
?>
