<?	
	function processa_retorno_f2b($num_billing){

		global $con;

		$sql = "select
					IdLoja,
					IdContaReceber,
					IdContaReceberRecebimento,
					IdLocalCobranca
				from
					ContaReceberRecebimentoParametro
				where
					IdParametroRecebimento = 'NumeroF2B' and
					ValorParametro = $num_billing";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);

		$sql = "select
					IdLocalCobrancaParametro,
					ValorLocalCobrancaParametro
				from
					LocalCobrancaParametro
				where
					IdLoja = $lin[IdLoja] and
					IdLocalCobrancaLayout = 21 and
					IdLocalCobranca = $lin[IdLocalCobranca]";
		$res = mysql_query($sql,$con);
		while($lin = mysql_fetch_array($res)){
			$LocalCobrancaParametro[$lin[IdLocalCobrancaParametro]] = $lin[ValorLocalCobrancaParametro];
		}

		// Tratamento de variáveis
		$LocalCobrancaParametro[Conta] = trim(str_replace(' ','',$LocalCobrancaParametro[Conta]));

		include("F2bSituacaoCobranca/WSBillingStatusPHP.php");
	}
?>