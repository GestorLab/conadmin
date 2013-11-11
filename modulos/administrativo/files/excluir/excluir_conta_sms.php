<?	
	$localModulo		=	1;
	$localOperacao		=	181;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login			=	$_SESSION['Login'];	
	$local_IdContaSMS		=	$_GET['IdContaSMS'];
	$local_IdOperadora		= 	$_GET['IdOperadora'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql3 = "SELECT 
					IdParametroOperadoraSMS
				FROM
					OperadoraSMSParametro
				WHERE
					IdOperadora = $local_IdOperadora";
		$res3 = mysql_query($sql3, $con);
		while($lin3 = mysql_fetch_array($res3)){		
				$sql =	"DELETE FROM ContaSMSParametro  WHERE IdContaSMS = $local_IdContaSMS and IdParametroOperadoraSMS = $lin3[IdParametroOperadoraSMS]";
				$res = mysql_query($sql,$con);
		}	
		$sql	=	"DELETE FROM ContaSMS WHERE IdContaSMS=$local_IdContaSMS";

		if(mysql_query($sql,$con)==true){
			echo $local_Erro = 7;
		}else{
			echo $local_Erro = 6;
		}
	}
?>