<?
	$localModulo		=	1;
	$localOperacao		=	52;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdEstoque	=	$_GET['IdEstoque'];
	$local_IdLoja		=	$_SESSION['IdLoja'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM Estoque WHERE IdLoja = $local_IdLoja and IdEstoque=$local_IdEstoque;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
