<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	}else{
		$sql	=	"select (max(IdLoteRepasse)+1) IdLoteRepasse from LoteRepasseTerceiro";
		$res	=	mysql_query($sql,$con);
		$lin	=	@mysql_fetch_array($res);
			
		if($lin[IdLoteRepasse] != NULL){
			$local_IdLoteRepasse	=	$lin[IdLoteRepasse];
		}else{
			$local_IdLoteRepasse	=	1;
		}
		
		if($local_Filtro_MenorVencimento != ''){
			$local_Filtro_MenorVencimento = "'".dataConv($local_Filtro_MenorVencimento, 'd/m/Y', 'Y-m-d')."'";
		} else{
			$local_Filtro_MenorVencimento = "(NULL)";
		}
		
		$local_Filtro_IdAgenteAutorizadoCarteira = explode(",", $local_Filtro_IdAgenteAutorizadoCarteira);
		$local_Filtro_IdAgenteAutorizado = '';
		$local_Filtro_IdCarteira = '';
		
		for($i = 0; $i < count($local_Filtro_IdAgenteAutorizadoCarteira); $i++) {
			list($local_Filtro_IdAgenteAutorizadoTemp, $local_Filtro_IdCarteiraTemp) = explode("_", $local_Filtro_IdAgenteAutorizadoCarteira[$i]);
			
			if($i > 0) {
				$local_Filtro_IdAgenteAutorizado .= ",";
				$local_Filtro_IdCarteira .= ",";
			}
			
			$local_Filtro_IdAgenteAutorizado .= $local_Filtro_IdAgenteAutorizadoTemp;
			$local_Filtro_IdCarteira .= $local_Filtro_IdCarteiraTemp;
		}
		
		if($local_Filtro_IdAgenteAutorizado == ''){
			$local_Filtro_IdAgenteAutorizado = "NULL";
		} else {
			$local_Filtro_IdAgenteAutorizado = "'$local_Filtro_IdAgenteAutorizado'";
		}
		
		if($local_Filtro_IdCarteira == ''){
			$local_Filtro_IdCarteira = "NULL";
		} else {
			$local_Filtro_IdCarteira = "'$local_Filtro_IdCarteira'";
		}
		
		if($local_Filtro_IdPaisEstadoCidade == ''){
			$local_Filtro_IdPaisEstadoCidade = "NULL";
		} else {
			$local_Filtro_IdPaisEstadoCidade = "'$local_Filtro_IdPaisEstadoCidade'";
		}
		
		$sql	=	"INSERT INTO LoteRepasseTerceiro SET 
					IdLoja						= '$local_IdLoja',
					IdLoteRepasse				= '$local_IdLoteRepasse',
					IdTerceiro					= '$local_IdPessoa',
					ObsRepasse					= '$local_ObsRepasse',
					IdStatus					=  1,
					Filtro_MesReferencia		= '$local_Filtro_MesReferencia',
					Filtro_IdServico			= '$local_Filtro_IdServico',
					Filtro_IdPessoa				= '$local_Filtro_IdPessoa',
					Filtro_IdLocalRecebimento	= '$local_Filtro_IdLocalRecebimento',
					Filtro_IdAgenteAutorizado	=  $local_Filtro_IdAgenteAutorizado,
					Filtro_IdCarteira        	=  $local_Filtro_IdCarteira,
					Filtro_MenorVencimento		=  $local_Filtro_MenorVencimento,
					Filtro_IdPaisEstadoCidade	=  $local_Filtro_IdPaisEstadoCidade,
					DataCriacao					=  concat(curdate(),' ',curtime()),
					LoginCriacao				= '$local_Login';";
					
		if(mysql_query($sql,$con) == true){
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserчуo Positiva
		}else{
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserчуo Negativa
		}
	}
?>