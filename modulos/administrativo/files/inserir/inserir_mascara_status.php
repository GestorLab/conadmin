<?
	if(permissaoSubOperacao($localModulo, $localOperacao, "I") == false) {
		$local_Erro = 2;
	} else {
		if($local_PercentualDesconto != '') {
			$local_PercentualDesconto = str_replace(array('.', ','), array('', '.'), $local_PercentualDesconto);
		}
		
		if($local_TaxaMudancaStatus != '') {
			$local_TaxaMudancaStatus = str_replace(array('.', ','), array('', '.'), $local_TaxaMudancaStatus);
		}
		
		$sql = "START TRANSACTION;";
		@mysql_query($sql, $con);
		
		$tr_i = 0;
		$sql = "INSERT INTO ServicoMascaraStatus SET 
					IdLoja				= '$local_IdLoja',
					IdServico			= '$local_IdServico',
					IdStatus			= '$local_IdStatus',
					PercentualDesconto	= '$local_PercentualDesconto',
					TaxaMudancaStatus	= '$local_TaxaMudancaStatus',
					QtdMinimaDia 		= '$local_QtdMinimaDia',
					LoginCriacao		= '$local_Login',
					DataCriacao			= concat(curdate(),' ',curtime());";
		$local_transaction[$tr_i] = mysql_query($sql, $con);
		$tr_i++;
		
		for($i = 0; $i < $tr_i; $i++) {
			if(!$local_transaction[$i]) {
				$local_transaction = false;	
				break;
			}
		}
		
		if($local_transaction) {
			$sql = "COMMIT;";
			$local_Acao = "alterar";
			$local_Erro = 3;			// Mensagem de Inserчуo Positiva
		} else {
			$sql = "ROLLBACK;";
			$local_Acao = "inserir";
			$local_Erro = 8;			// Mensagem de Inserчуo Negativa
		}
		
		@mysql_query($sql, $con);
	}
?>