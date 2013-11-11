<?
	$localModulo		=	1;
	$localOperacao		=	14;
	
	$local_IdPais		=	$_GET['IdPais'];
	$local_IdEstado		=	$_GET['IdEstado'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM Estado WHERE IdPais=$local_IdPais and IdEstado=$local_IdEstado;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
