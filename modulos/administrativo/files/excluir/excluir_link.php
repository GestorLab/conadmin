<?
	$localModulo		=	1;
	$localOperacao		=	37;
	
	$local_IdLoja		=	$_SESSION["IdLoja"];	
	$local_IdLink		=	$_GET['IdLink'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM Link WHERE IdLoja = $local_IdLoja and IdLink=$local_IdLink;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>

