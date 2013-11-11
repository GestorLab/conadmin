<?
	if(!permissaoSubOperacao($localModulo,$localOperacao,"I")){
		$local_Erro = 2;
	} else{
		$sql = "START TRANSACTION;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		$sql = "SELECT (MAX(IdCaixa) + 1) IdCaixa FROM Caixa WHERE IdLoja = '$local_IdLoja';";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdCaixa] != NULL){ 
			$local_IdCaixa = $lin[IdCaixa];
		} else{
			$local_IdCaixa = 1;
		}
		
		$sql = "INSERT INTO 
					Caixa 
				SET
					IdLoja			= '$local_IdLoja',
					IdCaixa			= '$local_IdCaixa',
					IdStatus		= '1',
					LoginAbertura	= '$local_Login',
					DataAbertura	= (concat(curdate(),' ',curtime()));";
		$local_transaction[$tr_i] = @mysql_query($sql,$con);
		$tr_i++;
		
		$local_FormaPagamento = explode(",", $local_FormaPagamento);
		
		for($i = 1; $i < count($local_FormaPagamento); $i++){
			$ValorAbertura = str_replace(",", ".", $_POST["ValorAbertura_".$local_FormaPagamento[$i]]);
			$sql = "INSERT INTO
						CaixaFormaPagamento
					SET
						IdLoja				= '$local_IdLoja',
						IdCaixa				= '$local_IdCaixa',
						IdFormaPagamento	= '".$local_FormaPagamento[$i]."',
						ValorAbertura		= '".$ValorAbertura."';";
			$local_transaction[$tr_i] = @mysql_query($sql,$con);
			$tr_i++;
		}
		
		if(!in_array(false, $local_transaction)){
			$sql = "COMMIT;";
			$local_Acao = 'alterar';
			$local_Erro = 3;			// Mensagem de Inserчуo Positiva
		} else{
			$sql = "ROLLBACK;";
			$local_Acao = 'inserir';
			$local_Erro = 8;			// Mensagem de Inserчуo Negativa
		}
		
		@mysql_query($sql,$con);
	}
?>