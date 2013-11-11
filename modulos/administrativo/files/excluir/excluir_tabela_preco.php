<?
	$localModulo		=	1;
	$localOperacao		=	54;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja			=	$_SESSION['IdLoja'];
	$local_IdTabelaPreco	=	$_GET['IdTabelaPreco'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM TabelaPreco WHERE IdLoja = $local_IdLoja and IdTabelaPreco=$local_IdTabelaPreco;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
