<?
	if(permissaoSubOperacao($localModulo, $localOperacao, "I") == false){
		$local_Erro = 2;
	} else{
		$sql = "START TRANSACTION;";
		@mysql_query($sql,$con);
		$tr_i = 0;
		
		// Sql de Inserчуo de FormaPagamento
		$sql = "SELECT (MAX(IdFormaPagamento)+1) IdFormaPagamento FROM FormaPagamento WHERE IdLoja = $local_IdLoja;";
		$res = @mysql_query($sql,$con);
		$lin = @mysql_fetch_array($res);
		
		if($lin[IdFormaPagamento] != NULL){
			$local_IdFormaPagamento = $lin[IdFormaPagamento];
		} else{
			$local_IdFormaPagamento = 1;
		}
		
		$sql = "INSERT INTO FormaPagamento SET
					IdLoja					= '$local_IdLoja',
					IdFormaPagamento		= '$local_IdFormaPagamento',
					DescricaoFormaPagamento	= '$local_DescricaoFormaPagamento',
					LoginCriacao			= '$local_Login', 
					DataCriacao				= (concat(curdate(),' ',curtime()));";
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
			$local_Acao = "alterar";	// Desabilita o inserir e Habilita alterar e excluir
			$local_Erro = 3;			// Mensagem de Inserчуo Positiva
		} else{
			$sql = "ROLLBACK;";
			$local_Acao = "inserir";
			$local_Erro = 8;			// Mensagem de Inserчуo Negativa
		}
		
		@mysql_query($sql,$con);
	}
?>