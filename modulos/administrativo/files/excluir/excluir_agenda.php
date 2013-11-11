<?	
	$localModulo		=	1;
	$localOperacao		=	38;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login		=	$_SESSION['Login'];	
	$local_IdAgenda		=	$_GET['IdAgenda'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		 $sql	=	"DELETE FROM Agenda WHERE Login = '$local_Login' and IdAgenda=$local_IdAgenda;";
		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 33;
		}
	}
?>
