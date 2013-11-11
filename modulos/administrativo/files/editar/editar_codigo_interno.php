<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE CodigoInterno SET
							DescricaoCodigoInterno	= '$local_DescricaoCodigoInterno', 
							ValorCodigoInterno		='$local_ValorCodigoInterno',
							LoginAlteracao			='$local_Login',
							DataAlteracao			= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja					= $local_IdLoja and
							IdGrupoCodigoInterno	= '$local_IdGrupoCodigoInterno' and
							IdCodigoInterno			= '$local_IdCodigoInterno'";
							
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
