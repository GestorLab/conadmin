<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{		
		 $sql	=	"UPDATE Device
						SET 
							IdTipoDevice 	= '$local_IdTipoDevice',
							DescricaoDevice = '$local_DescricaoDevice',
							IdGrupoDevice 	= '$local_IdGrupoDevice',
							Observacao 		= '$local_Observacao',
							LoginCriacao 	= '$local_Login',
							LoginAlteracao 	= '$local_Login',
							DataAlteracao 	= (concat(curdate(),' ',curtime()))
						WHERE 
							IdLoja = '$local_IdLoja'
							AND IdDevice = '$local_IdDevice'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
