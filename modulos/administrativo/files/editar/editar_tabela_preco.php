<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE TabelaPreco SET 
							DescricaoTabelaPreco	='$local_DescricaoTabelaPreco',
							LoginAlteracao			='$local_Login',
							DataAlteracao			= concat(curdate(),' ',curtime())
						WHERE 	
							IdLoja					= '$local_IdLoja' and
							IdTabelaPreco			= '$local_IdTabelaPreco'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
