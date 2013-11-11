<?
	$localModulo		=	1;
	$localOperacao		=	9;
	
	$local_Login			= $_GET['Login'];
	$local_IdGrupoPermissao = $_GET['IdGrupoPermissao'];
	$local_Acao				= $_GET['Acao'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
		
	$local_Login_Sistema	= $_SESSION["Login"];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		echo $local_Erro = 2;
	}else{
	
			switch($local_Acao){
				case "add":					
					$sql = "INSERT INTO UsuarioGrupoPermissao
						      (Login, IdGrupoPermissao, LoginCriacao, DataCriacao)
							VALUES
						      ('$local_Login', $local_IdGrupoPermissao, '$local_Login_Sistema',concat(curdate(),' ',curtime()));";
					if(mysql_query($sql,$con) == true){
						echo $local_Erro = 36;
					}else{
						echo $local_Erro = 35;
					}	
					break;
					
				case "rem":
					$sql = "DELETE FROM UsuarioGrupoPermissao WHERE Login='$local_Login' AND IdGrupoPermissao=$local_IdGrupoPermissao;";
					if(mysql_query($sql,$con) == true){
						echo $local_Erro = 37;
					}else{
						echo $local_Erro = 38;
					}				
					break;
			}
	}
?>
