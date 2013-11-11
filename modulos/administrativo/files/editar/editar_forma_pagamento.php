<?
	if(permissaoSubOperacao($localModulo, $localOperacao, "U") == false){
		$local_Erro = 2;
	} else{
		$sql = "START TRANSACTION;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql = "UPDATE FormaPagamento SET 
					DescricaoFormaPagamento	='$local_DescricaoFormaPagamento',
					LoginAlteracao			='$local_Login',
					DataAlteracao			= concat(curdate(),' ',curtime())
				WHERE 	
					IdLoja					= '$local_IdLoja' and
					IdFormaPagamento		= '$local_IdFormaPagamento'";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		$tr_i++;
		
		$sql = "DELETE FROM 
					FormaPagamentoParcela 
				WHERE 	
					IdLoja					= '$local_IdLoja' and
					IdFormaPagamento		= '$local_IdFormaPagamento'";
		$local_transaction[$tr_i] = @mysql_query($sql, $con);
		$tr_i++;
		
		for($i = 1; $i <= $local_QTDParcelas; $i++){
			$QtdParcela = $i;
			$PercentualJurosMes = str_replace(",", ".", $_POST["PercentualJurosMes_".$i]);
			
			$sql = "INSERT INTO FormaPagamentoParcela SET 
						IdLoja				= '$local_IdLoja', 
						IdFormaPagamento	= '$local_IdFormaPagamento', 
						QtdParcela			= '$QtdParcela', 
						PercentualJurosMes	= '$PercentualJurosMes';";
			$local_transaction[$tr_i] = @mysql_query($sql, $con);
			$tr_i++;
		}
		
		if(!in_array(false, $local_transaction)){
			$sql = "COMMIT;";
			$local_Erro = 4;
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 5;
		}
		
		@mysql_query($sql,$con);
	}
?>