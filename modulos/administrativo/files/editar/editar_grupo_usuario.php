<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		$local_Erro = 2;
	}else{
		
		$sql	=	"UPDATE GrupoUsuario SET 
							DescricaoGrupoUsuario	='$local_DescricaoGrupoUsuario',
							OrdemServico			='$local_OrdemServico',
							LoginAlteracao			='$local_Login',
							LoginSupervisor			='$local_LoginSupervisor',
							DataAlteracao			= concat(curdate(),' ',curtime())
						WHERE 
							IdLoja					='$local_IdLoja' and
							IdGrupoUsuario			= '$local_IdGrupoUsuario'";
		if(mysql_query($sql,$con) == true){
			$local_Erro = 4;
		}else{
			$local_Erro = 5;
		}
	}
?>
