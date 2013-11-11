<?	
	$localModulo		=	1;
	$localOperacao		=	81;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja			=	$_SESSION['IdLoja'];	
	$local_IdContaReceber	=	$_GET['IdContaReceber'];
	$local_DataVencimento	=	$_GET['DataVencimento'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		 $sql	=	"DELETE FROM ContaReceberVencimento WHERE IdLoja = $local_IdLoja and IdContaReceber=$local_IdContaReceber and DataVencimento='$local_DataVencimento';";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 6;
		}
	}
?>
