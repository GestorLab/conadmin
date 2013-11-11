<?
	$localModulo		=	1;
	$localOperacao		=	20;
	
	$local_IdLoja			=	$_SESSION["IdLoja"];
	$local_IdCentroCusto	=	$_GET['IdCentroCusto'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM CentroCusto WHERE IdLoja=$local_IdLoja and IdCentroCusto=$local_IdCentroCusto;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
