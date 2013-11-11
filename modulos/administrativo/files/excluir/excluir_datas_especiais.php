<?
	$localModulo		=	1;
	$localOperacao		=	39;
	
	$local_IdLoja		=	$_SESSION["IdLoja"];	
	$local_Data			=	$_GET['Data'];

	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM DatasEspeciais WHERE IdLoja=$local_IdLoja and Data='$local_Data'";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
