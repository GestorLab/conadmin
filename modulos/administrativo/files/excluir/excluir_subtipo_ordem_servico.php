<?
	$localModulo		=	1;
	$localOperacao		=	83;
	
	$local_IdLoja					=	$_SESSION["IdLoja"];	
	$local_IdTipoOrdemServico		=	$_GET['IdTipoOrdemServico'];
	$local_IdSubTipoOrdemServico	=	$_GET['IdSubTipoOrdemServico'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM SubTipoOrdemServico WHERE IdLoja = $local_IdLoja and IdTipoOrdemServico=$local_IdTipoOrdemServico and IdSubTipoOrdemServico=$local_IdSubTipoOrdemServico;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 6;
		}
	}
?>
