<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE SubGrupoProduto SET 
							DescricaoSubGrupoProduto	='$local_DescricaoSubGrupoProduto',
							LoginAlteracao				='$local_Login',
							DataAlteracao				= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja						= $local_IdLoja and
							IdGrupoProduto				= '$local_IdGrupoProduto' and
							IdSubGrupoProduto			= '$local_IdSubGrupoProduto'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
