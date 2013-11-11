<?
	$localModulo		=	1;
	$localOperacao		=	41;
	
	$local_IdGrupoUsuario	= $_GET['IdGrupoUsuario'];
	$local_IdQuadroAviso	= $_GET['IdQuadroAviso'];
	$local_Acao				= $_GET['Acao'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
		
	$IdLoja		= $_SESSION["IdLoja"];
	$Login		= $_SESSION["Login"];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"U") == false){
		echo $local_Erro = 2;
	}else{
	
			switch($local_Acao){
				case "add":					
					$sql = "INSERT INTO GrupoUsuarioQuadroAviso
						      (IdLoja, IdGrupoUsuario,IdQuadroAviso, LoginCriacao, DataCriacao)
							VALUES
						      ($IdLoja, $local_IdGrupoUsuario, $local_IdQuadroAviso, '$Login',concat(curdate(),' ',curtime()));";
					if(mysql_query($sql,$con) == true){
						echo $local_Erro = 36;
					}else{
						echo $local_Erro = 35;
					}	
					break;
					
				case "rem":
					$sql = "DELETE FROM GrupoUsuarioQuadroAviso WHERE IdGrupoUsuario=$local_IdGrupoUsuario AND IdQuadroAviso = $local_IdQuadroAviso AND IdLoja = $IdLoja;";
					if(mysql_query($sql,$con) == true){
						echo $local_Erro = 37;
					}else{
						echo $local_Erro = 38;
					}				
					break;
			}
	}
?>
