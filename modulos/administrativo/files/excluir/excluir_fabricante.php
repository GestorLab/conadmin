<?
	$localModulo		=	1;
	$localOperacao		=	47;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja		=	$_SESSION['IdLoja'];
	$local_IdFabricante	=	$_GET['IdFabricante'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM Fabricante WHERE IdLoja = $local_IdLoja and IdFabricante=$local_IdFabricante;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
