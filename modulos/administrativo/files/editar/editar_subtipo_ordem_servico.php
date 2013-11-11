<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE SubTipoOrdemServico SET 
							DescricaoSubTipoOrdemServico	='$local_DescricaoSubTipoOrdemServico',
							Cor								='$local_Cor',
							LoginAlteracao					='$local_Login',
							DataAlteracao					= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja							= $local_IdLoja and
							IdTipoOrdemServico				= '$local_IdTipoOrdemServico' and
							IdSubTipoOrdemServico			= '$local_IdSubTipoOrdemServico'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
