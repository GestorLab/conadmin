<?
	$localModulo		=	1;
	$localOperacao		=	32;
	
	$local_IdLoja			=	$_SESSION["IdLoja"];	
	$local_IdGrupoUsuario	=	$_GET['IdGrupoUsuario'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM GrupoUsuario WHERE IdLoja='$local_IdLoja' and IdGrupoUsuario=$local_IdGrupoUsuario;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
