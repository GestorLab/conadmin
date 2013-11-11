<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"select (max(IdSubTipoHelpDesk)+1) IdSubTipoHelpDesk from HelpDeskSubTipo where IdTipoHelpDesk = $local_IdTipo;";
		$res	=	@mysql_query($sql,$conCNT);
		$lin	=	@mysql_fetch_array($res);
		
		if($lin[IdSubTipoHelpDesk]!=NULL){ 
			$local_IdSubTipo	= $lin[IdSubTipoHelpDesk];
		}else{
			$local_IdSubTipo	= 1;
		}
		
		$sql	=	"
					INSERT INTO
						HelpDeskSubTipo
					SET
						IdTipoHelpDesk				=  $local_IdTipo,
						IdSubTipoHelpDesk			=  $local_IdSubTipo,
						DescricaoSubTipoHelpDesk	= '$local_DescricaoSubTipo',  
						IdStatus					=  $local_IdStatusSubTipo,
						DataCriacao					=  (concat(curdate(),' ',curtime())),
						LoginCriacao				= '$local_Login'";
		if(mysql_query($sql,$conCNT) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserчуo Positiva
		} else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserчуo Negativa
		}
	}
?>