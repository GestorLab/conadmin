<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE GrupoPessoa SET
							DescricaoGrupoPessoa	= '$local_DescricaoGrupoPessoa',
							LoginAlteracao			='$local_Login',
							DataAlteracao			= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja 					= $local_IdLoja AND
							IdGrupoPessoa			= $local_IdGrupoPessoa";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
