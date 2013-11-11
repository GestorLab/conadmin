<?	
	$localModulo		=	1;
	$localOperacao		=	135;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login		=	$_SESSION['Login'];	
	$local_IdLoja		=	$_SESSION['IdLoja'];	
	$local_IdFormaAvisoCobranca		=	$_GET['IdFormaAvisoCobranca'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM FormaAvisoCobranca WHERE IdLoja = $local_IdLoja and IdFormaAvisoCobranca=$local_IdFormaAvisoCobranca;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 6;
		}
	}
?>
