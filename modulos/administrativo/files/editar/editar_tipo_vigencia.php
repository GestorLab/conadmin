<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE ContratoTipoVigencia SET 
							DescricaoContratoTipoVigencia	='$local_DescricaoContratoTipoVigencia',
							Isento							='$local_Isento',
							LoginAlteracao					='$local_Login',
							DataAlteracao					= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja							= $local_IdLoja and
							IdContratoTipoVigencia			= '$local_IdContratoTipoVigencia'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
