<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE Carteira SET 
							IdStatus					= $local_IdStatus,
							Restringir					= $local_Restringir,
							LoginAlteracao				='$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja						= $local_IdLoja and
							IdCarteira					= '$local_IdCarteira' and
							IdAgenteAutorizado			= '$local_IdAgenteAutorizado'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
