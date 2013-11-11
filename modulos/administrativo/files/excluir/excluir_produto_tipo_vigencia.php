<?
	$localModulo		=	1;
	$localOperacao		=	50;
	
	$local_IdLoja					=	$_SESSION["IdLoja"];	
	$local_IdProdutoTipoVigencia	=	$_GET['IdProdutoTipoVigencia'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM ProdutoTipoVigencia WHERE IdLoja = $local_IdLoja and IdProdutoTipoVigencia=$local_IdProdutoTipoVigencia;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
