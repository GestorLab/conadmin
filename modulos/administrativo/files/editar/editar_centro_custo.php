<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE CentroCusto SET
							DescricaoCentroCusto	= '$local_DescricaoCentroCusto', 
							LoginAlteracao			='$local_Login',
							IdStatus				='$local_IdStatus',
							DataAlteracao			= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja			= '$local_IdLoja' and
							IdCentroCusto	= '$local_IdCentroCusto'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
