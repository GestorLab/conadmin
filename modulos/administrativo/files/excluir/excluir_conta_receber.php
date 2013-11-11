<?
	$localModulo		=	1;
	$localOperacao		=	58;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_Login				=	$_SESSION['Login'];	
	$local_IdContaReceber		=	$_GET['IdContaReceber'];
	$local_Obs					= "".date("d/m/Y")." ".date("H:i:s")." [$local_Login] - Contas a receber excluído via operações.\n";
	
	$sql_Obs = "SELECT Obs FROM ContaReceber WHERE IdContaReceber = '$local_IdContaReceber'";
	$res_Obs = @mysql_query($sql_Obs,$con);
	$lin_Obs = @mysql_fetch_array($res_Obs);
	$local_Obs	.= $lin_Obs[Obs];
		
	if(permissaoSubOperacao($localModulo,$localOperacao,"D") == false){
		echo $local_Erro = 2;
	}else{
		$sql	=	"START TRANSACTION;";
		mysql_query($sql,$con);		
		$tr_i = 0;		
		$sql	=	"UPDATE ContaReceber SET
						IdStatus				= '7',
						LoginAlteracao			= '$local_Login',
						Obs						= '$local_Obs',
						DataAlteracao			= concat(curdate(),' ',curtime())
					WHERE 
						IdLoja					= '$local_IdLoja' and
						IdContaReceber			= '$local_IdContaReceber'";
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
