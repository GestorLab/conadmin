<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE Estoque SET 
							DescricaoEstoque	='$local_DescricaoEstoque',
							LoginAlteracao		='$local_Login',
							DataAlteracao		= concat(curdate(),' ',curtime())
						WHERE 	
							IdLoja				= '$local_IdLoja' and
							IdEstoque			= '$local_IdEstoque'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
