<?
	$local_SenhaAntiga = md5($local_SenhaAntiga);
	
	$sql =	"select 
					Login 
				from 
					Usuario 
				where  
					Login = '$local_Login'and 
					Password='$local_SenhaAntiga';";
	$res	= mysql_query($sql,$con);
    if($lin	= mysql_fetch_array($res)){
		
		include ('files/editar/editar_usuario_alterar_senha_radius.php');
		include ('../../files/conecta.php');
		
		$local_NovaSenha = md5($local_NovaSenha);
		
		$sql	=	"UPDATE Usuario SET 				
						Password    			= '$local_NovaSenha',
						LoginAlteracao			= '$local_Login',
						ForcarAlteracaoSenha	= 2,
						DataAlteracao			= concat(curdate(),' ',curtime())
					WHERE
						Login='$local_Login';";
		if(mysql_query($sql,$con)){
			$local_Erro = 4;
		} else{
			$local_Erro = 5;
		}
	} else{
		$local_Erro = 196;
	}
?>
