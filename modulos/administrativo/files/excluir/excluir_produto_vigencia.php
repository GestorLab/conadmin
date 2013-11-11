<?
	$localModulo		=	1;
	$localOperacao		=	46;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja				=	$_SESSION['IdLoja'];
	$local_IdProduto			=	$_GET['IdProduto'];
	$local_DataInicio			=	$_GET['DataInicio'];
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM ProdutoVigencia WHERE IdLoja = $local_IdLoja and IdProduto=$local_IdProduto and DataInicio = '$local_DataInicio';";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 6;
		}
	}
?>
