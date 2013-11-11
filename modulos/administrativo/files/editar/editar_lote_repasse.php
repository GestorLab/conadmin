<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
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
		
		$sql	=	"UPDATE LoteRepasseTerceiro SET
							IdTerceiro					= '$local_IdPessoa',
							ObsRepasse					= '$local_ObsRepasse',
							Filtro_MesReferencia		= '$local_Filtro_MesReferencia',
							Filtro_IdServico			= '$local_Filtro_IdServico',
							Filtro_IdPessoa				= '$local_Filtro_IdPessoa',
							Filtro_IdLocalRecebimento	= '$local_Filtro_IdLocalRecebimento',
							Filtro_IdAgenteAutorizado	=  $local_Filtro_IdAgenteAutorizado,
							Filtro_IdCarteira        	=  $local_Filtro_IdCarteira,
							Filtro_MenorVencimento		=  $local_Filtro_MenorVencimento,
							Filtro_IdPaisEstadoCidade	=  $local_Filtro_IdPaisEstadoCidade,
							DataAlteracao				=  concat(curdate(),' ',curtime()),
							LoginAlteracao				= '$local_Login'
					 WHERE 
							IdLoja						= $local_IdLoja and
							IdLoteRepasse				= $local_IdLoteRepasse";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>