<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		if($local_LimiteVisualizacao == ""){
			$local_LimiteVisualizacao = 'NULL';	
		}
		
		$sql	=	"UPDATE GrupoPermissao SET 
							DescricaoGrupoPermissao	='$local_DescricaoGrupoPermissao',
							LimiteVisualizacao		= $local_LimiteVisualizacao,
							IpAcesso				='$local_IpAcesso',
							LoginAlteracao			='$local_Login',
							DataAlteracao			= concat(curdate(),' ',curtime())
						WHERE 
							IdGrupoPermissao		= '$local_IdGrupoPermissao'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
