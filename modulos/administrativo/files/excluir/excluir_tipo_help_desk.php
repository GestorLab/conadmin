<?	
	$localModulo		=	1;
	$localOperacao		=	65;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login		=	$_SESSION['Login'];	
	$local_IdTipo		=	$_GET['IdTipo'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"DELETE FROM HelpDeskTipo WHERE IdTipoHelpDesk = $local_IdTipo;";
		
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
