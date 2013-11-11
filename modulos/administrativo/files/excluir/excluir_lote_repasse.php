<?	
	$localModulo		=	1;
	$localOperacao		=	42;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login			=	$_SESSION['Login'];	
	$local_IdLoja			=	$_SESSION['IdLoja'];	
	$local_IdLoteRepasse	=	$_GET['IdLoteRepasse'];

	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);
		
		$tr_i = 0;	

		$sql	=	"DELETE FROM LoteRepasseTerceiroItem WHERE IdLoja = '$local_IdLoja' and IdLoteRepasse=$local_IdLoteRepasse;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
		
		$sql	=	"DELETE FROM LoteRepasseTerceiro WHERE IdLoja = '$local_IdLoja' and IdLoteRepasse=$local_IdLoteRepasse;";
		$local_transaction[$tr_i]	=	mysql_query($sql,$con);
		$tr_i++;
	
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			echo $local_Erro = 7;
			$sql = "COMMIT;";
		}else{
			echo $local_Erro = 6;
			$sql = "ROLLBACK;";
		}
		mysql_query($sql,$con);
	}
?>
