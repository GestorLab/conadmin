<?
	$localModulo		=	1;
	$localOperacao		=	48;
	
	$local_IdLoja			=	$_SESSION["IdLoja"];	
	$local_IdGrupoProduto	=	$_GET['IdGrupoProduto'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM GrupoProduto WHERE IdLoja = $local_IdLoja and IdGrupoProduto=$local_IdGrupoProduto;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
