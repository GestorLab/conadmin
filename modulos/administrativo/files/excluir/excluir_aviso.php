<?	
	$localModulo		=	1;
	$localOperacao		=	74;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login		=	$_SESSION['Login'];	
	$local_IdLoja		=	$_SESSION['IdLoja'];
	$local_IdAviso		=	$_GET['IdAviso'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		$tr_i = 0;	

		$sql = "select
					count(*) Qtd
				from
					AvisoLeitura
				where		
					IdAviso = $local_IdAviso";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);				

		$sql	=	"DELETE FROM Aviso WHERE IdLoja = $local_IdLoja and IdAviso=$local_IdAviso;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;		

		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true && $lin[Qtd] < 1){
			echo $local_Erro = 7;
			$sql = "COMMIT;";
		}else{
			echo $local_Erro = 6;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>