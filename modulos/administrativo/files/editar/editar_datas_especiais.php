<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE DatasEspeciais SET
							TipoData		= '$local_TipoData',
							DescricaoData	= '$local_DescricaoData',
							LoginAlteracao	='$local_Login',
							DataAlteracao	= concat(curdate(),' ',curtime())
					 WHERE 
							Data			= '".dataConv($local_Data,'d/m/Y','Y-m-d')."'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
