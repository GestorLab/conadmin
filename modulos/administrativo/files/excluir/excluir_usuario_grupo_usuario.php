<?
	$localModulo		=	1;
	$localOperacao		=	33;
	
	$local_IdLoja			=	$_SESSION["IdLoja"];
	$local_IdGrupoUsuario	=	$_GET['IdGrupoUsuario'];
	$local_Login			=	$_GET['Login'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM UsuarioGrupoUsuario WHERE IdLoja='$local_IdLoja' and IdGrupoUsuario=$local_IdGrupoUsuario and Login='$local_Login';";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
