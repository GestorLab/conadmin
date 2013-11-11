<?
	$localModulo		=	1;
	$localOperacao		=	53;
	
	include ('../../../../files/conecta.php');
	include ('../../../../files/funcoes.php');
	include ('../../../../rotinas/verifica.php');
	
	$local_IdLoja			=	$_SESSION['IdLoja'];
	$local_IdFormaPagamento	=	$_GET['IdFormaPagamento'];
	
	if(permissaoSubOperacao($localModulo, $localOperacao, "D") == false){
		echo $local_Erro = 2;
	} else{
		$sql = "SELECT 
					IdCaixa 
				FROM 
					CaixaFormaPagamento 
				WHERE 
					IdLoja = $local_IdLoja AND 
					IdFormaPagamento = $local_IdFormaPagamento";
		$res = @mysql_query($sql, $con);
		
		if(@mysql_num_rows($res) == 0) {
			echo $local_Erro = 33;
		} else {
			$sql = "START TRANSACTION;";
			@mysql_query($sql,$con);
			$tr_i = 0;
			
			$sql = "DELETE FROM FormaPagamentoParcela WHERE IdLoja = $local_IdLoja and IdFormaPagamento=$local_IdFormaPagamento;";
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
			
			$sql = "DELETE FROM FormaPagamento WHERE IdLoja = $local_IdLoja and IdFormaPagamento=$local_IdFormaPagamento;";
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
			
			if(!in_array(false, $local_transaction)){
				$sql = "COMMIT;";
				echo $local_Erro = 7;
			} else{
				$sql = "ROLLBACK;";
				echo $local_Erro = 33;
			}
			
			@mysql_query($sql,$con);
		}
	}
?>