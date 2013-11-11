<?
	$localModulo		=	1;
	$localOperacao		=	33;
	
	$local_IdGrupoUsuario	= $_GET['IdGrupoUsuario'];
	$local_Login			= $_GET['Login'];
	$local_Acao				= $_GET['Acao'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
		
	$IdLoja				= $_SESSION["IdLoja"];
	$LoginSistema		= $_SESSION["Login"];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		echo $local_Erro = 2;
	}else{
		switch($local_Acao){
			case "add":					
				$sql = "INSERT INTO UsuarioGrupoUsuario
					      (IdLoja, IdGrupoUsuario,Login, LoginCriacao, DataCriacao)
						VALUES
					      ($IdLoja, $local_IdGrupoUsuario, '$local_Login', '$LoginSistema',concat(curdate(),' ',curtime()));";
				if(mysql_query($sql,$con) == true){
					echo $local_Erro = 36;
				}else{
					echo $local_Erro = 35;
				}	
				break;
				
			case "rem":
				$sql = "DELETE FROM UsuarioGrupoUsuario WHERE IdLoja = '$IdLoja' and IdGrupoUsuario=$local_IdGrupoUsuario AND Login = '$local_Login';";
				if(mysql_query($sql,$con) == true){
					echo $local_Erro = 37;
				}else{
					echo $local_Erro = 38;
				}				
				break;
		}
	}
?>
