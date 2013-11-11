<?
	$localModulo		=	1;
	$localOperacao		=	63;
	
	$local_IdLocalCobranca					=	$_GET['IdLocalCobranca'];
	$local_IdLocalCobrancaParametroContrato	=	$_GET['IdLocalCobrancaParametroContrato'];
	$local_IdLoja							=	$_SESSION["IdLoja"];
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM LocalCobrancaParametroContrato WHERE IdLoja = $local_IdLoja and IdLocalCobranca=$local_IdLocalCobranca and IdLocalCobrancaParametroContrato='$local_IdLocalCobrancaParametroContrato'";
		if(mysql_query($sql,$con) == true){
			echo $local_Erro = 7;		
		}else{
			echo $local_Erro = 6;		
		}
	}
?>
