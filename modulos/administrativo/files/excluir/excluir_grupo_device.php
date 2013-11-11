<?
	$localModulo		=	1;
	$localOperacao		=	12;
	
	$local_IdGrupoDevice		=	$_GET['IdGrupoDevice'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM GrupoDevice WHERE IdLoja=$local_IdLoja and IdGrupoDevice=$local_IdGrupoDevice;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
