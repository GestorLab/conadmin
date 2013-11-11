<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{		
		 $sql	=	"UPDATE GrupoDevice SET
							DescricaoGrupoDevice	= '$local_DescricaoGrupoDevice',
							LoginAlteracao			='$local_Login',
							DataAlteracao			= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja 					= $local_IdLoja AND
							IdGrupoDevice			= $local_IdGrupoDevice";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
