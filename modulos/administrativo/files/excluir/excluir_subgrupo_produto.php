<?
	$localModulo		=	1;
	$localOperacao		=	49;
	
	$local_IdLoja				=	$_SESSION["IdLoja"];	
	$local_IdGrupoProduto		=	$_GET['IdGrupoProduto'];
	$local_IdSubGrupoProduto	=	$_GET['IdSubGrupoProduto'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM SubGrupoProduto WHERE IdLoja = $local_IdLoja and IdGrupoProduto=$local_IdGrupoProduto and IdSubGrupoProduto=$local_IdSubGrupoProduto;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
