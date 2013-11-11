<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE Link SET
							DescricaoLink	= '$local_DescricaoLink',
							Link			= '$local_Link',
							LoginAlteracao	='$local_Login',
							DataAlteracao	= concat(curdate(),' ',curtime())
					 WHERE 
							IdLink			= $local_IdLink";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
