<?
	$localModulo		=	1;
	$localOperacao		=	64;
	
	$local_IdLoja				=	$_SESSION["IdLoja"];	
	$local_IdLocalCobranca		=	$_GET['IdLocalCobranca'];
	$local_IdArquivoRemessa		=	$_GET['IdArquivoRemessa'];
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM ArquivoRemessa WHERE IdLoja = $local_IdLoja and IdLocalCobranca=$local_IdLocalCobranca and IdArquivoRemessa=$local_IdArquivoRemessa;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
