<?
	$localModulo		=	1;
	$localOperacao		=	25;
	
	$local_IdServico			=	$_GET['IdServico'];
	$local_IdParametroServico	=	$_GET['IdParametroServico'];
	$local_IdLoja				=	$_SESSION["IdLoja"];
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM ServicoParametro WHERE IdLoja = $local_IdLoja and IdServico=$local_IdServico and IdParametroServico='$local_IdParametroServico'";
		if(mysql_query($sql,$con) == true){
			echo $local_Erro = 7;		
		}else{
			echo $local_Erro = 33;		
		}
	}
?>
