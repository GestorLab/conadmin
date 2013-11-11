<?
	if(permissaoSubOperacao($localModulo,$localOperacao,"I") == false){
		$local_Erro = 2;
	} else{
		$tr_i = 0;
		$sql = "START TRANSACTION;";
		mysql_query($sql,$con);
		
		$sql = "SELECT MAX(IdContaReceberAgrupador) IdContaReceberAgrupador FROM ContaReceberAgrupado WHERE IdLoja = $local_IdLoja";
		$res = mysql_query($sql,$con);
		$lin = mysql_fetch_array($res);
		$local_IdContaReceberAgrupador = $lin[IdContaReceberAgrupador];
		
		if($local_IdContaReceberAgrupador == null){
			$local_IdContaReceberAgrupador = 1;
		} else{
			$local_IdContaReceberAgrupador++;
		}
		
		$sql = "INSERT INTO ContaReceberAgrupado SET
					IdLoja						= '$local_IdLoja',
					IdContaReceberAgrupador		= '$local_IdContaReceberAgrupador',
					IdPessoa					= '$local_IdPessoa',
					IdPessoaEndereco			= '$local_IdPessoaEndereco',
					IdLocalCobranca				= '$local_IdLocalCobranca',
					ValorDespesaLocalCobranca	= '$local_ValorDespesaLocalCobranca',
					ValorDesconto				= '$local_ValorDescontoVencimento',
					ValorMulta					= '$local_ValorMoraMulta',
					ValorJuros					= '$local_ValorJurosVencimento',
					ValorOutrasDespesas			= '$local_ValorOutrasDespesas',
					ValorReImpressaoBoleto		= '$local_ValorTaxaReImpressaoBoleto',
					ValorTotal					= '$local_ValorFinalVencimento',
					QtdParcela					= '$local_QtdParcela',
					OcultarReferencia			= '$local_OcultarReferencia',
					IdStatus					= ".StatusContaReceber($local_IdLoja, $local_IdLocalCobranca).",
					LoginCriacao				= '$local_Login',
					DataCriacao					= concat(curdate(),' ',curtime());";
		$local_transaction[$tr_i] = mysql_query($sql,$con);
		$tr_i++;
		$local_IdContaReceberAgrupadosTemp = explode(",", $local_IdContaReceberAgrupados);
		
		for($i = 0; $i < count($local_IdContaReceberAgrupadosTemp); $i++){
			$sql = "INSERT INTO ContaReceberAgrupadoItem SET
						IdLoja					= '$local_IdLoja',
						IdContaReceber			= '$local_IdContaReceberAgrupadosTemp[$i]',
						IdContaReceberAgrupador	= '$local_IdContaReceberAgrupador';";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
			
			$sqlObs = "SELECT
							Obs
						FROM
							ContaReceber
						WHERE
							IdLoja = '$local_IdLoja' AND
							IdContaReceber = '$local_IdContaReceberAgrupadosTemp[$i];'";
			$resObs = mysql_query($sqlObs, $con);
			$linObs = mysql_fetch_array($resObs);
			$linObs[Temp] = str_replace("'", "\'", $linObs[Obs]);
			$linObs[Obs] = date("d/m/Y H:i:s")." [".$local_Login."] - Contas a receber utilizado no agrupamento Contas a receber nА $local_IdContaReceberAgrupador.";
			
			if($linObs[Temp] != ''){
				$linObs[Obs] .= "\n".$linObs[Temp];
			}
			
			$sql = "UPDATE ContaReceber SET
						Obs				= '$linObs[Obs]',
						IdStatus		= '8',
						LoginAlteracao	= '$local_Login',
						DataAlteracao	= concat(curdate(),' ',curtime())
					WHERE 
						IdLoja = '$local_IdLoja' AND 
						IdContaReceber = '$local_IdContaReceberAgrupadosTemp[$i]';";
			$local_transaction[$tr_i] = mysql_query($sql, $con);
			$tr_i++;
		}
		
		for($i = 1; $i <= $local_QtdParcela; $i++){
			$ValorParcelaTemp = (float)(str_replace(",", ".", $_POST["parcValor_".$i]));
			$ValorDespesaTemp = (float)(str_replace(",", ".", $_POST["parcDesp_".$i]));
			$ValorContaReceberTemp = ($ValorParcelaTemp + $ValorDespesaTemp);
			$DataVencimentoTemp = dataConv($_POST["parcData_".$i], "d/m/Y", "Y-m-d");
			
			$sql = "SELECT MAX(IdContaReceber) IdContaReceber FROM ContaReceber WHERE IdLoja = $local_IdLoja";
			$res = mysql_query($sql,$con);
			$lin = mysql_fetch_array($res);
			$IdContaReceberParcela = $lin[IdContaReceber];
			
			if($IdContaReceberParcela == null){
				$IdContaReceberParcela = 1;
			} else{
				$IdContaReceberParcela++;
			}
			
			$sql = "INSERT INTO ContaReceber SET 
						IdLoja				= '$local_IdLoja', 
						IdContaReceber		= '$IdContaReceberParcela',
						IdPessoa			= '$local_IdPessoa',
						IdPessoaEndereco	= '$local_IdPessoaEndereco', 
						ValorLancamento		= '$ValorParcelaTemp',
						ValorDespesas		= '$ValorDespesaTemp',
						DataLancamento		= '".date("Y-m-d")."', 
						NumeroDocumento		= NumeroDocumento($local_IdLoja, $local_IdLocalCobranca), 
						IdLocalCobranca		= '$local_IdLocalCobranca', 
						IdStatus			= '".StatusContaReceber($local_IdLoja, $local_IdLocalCobranca)."',
						LoginCriacao		= '$local_Login', 
						DataCriacao			= concat(curdate(),' ',curtime());";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
			
			posicaoCobranca($local_IdLoja, $IdContaReceberParcela, 1, $local_Login);
			
			$sql = "INSERT INTO ContaReceberVencimento SET 
						IdLoja						= '$local_IdLoja',
						IdContaReceber				= '$IdContaReceberParcela',
						DataVencimento				= '$DataVencimentoTemp', 
						ValorContaReceber			= '$ValorContaReceberTemp',
						ValorMulta					= '0.00',
						ValorJuros					= '0.00',
						ValorTaxaReImpressaoBoleto	= '0.00',
						ValorOutrasDespesas			= '0.00',
						ValorDesconto				= '0.00',
						LoginCriacao				= '$local_Login',
						DataCriacao					= concat(curdate(),' ',curtime());";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
			
			$sql = "SELECT MAX(IdLancamentoFinanceiro) IdLancamentoFinanceiro FROM LancamentoFinanceiro WHERE IdLoja = $local_IdLoja";
			$res = mysql_query($sql);
			$lin = mysql_fetch_array($res);
			$IdLancamentoFinanceiro = $lin[IdLancamentoFinanceiro];
			
			if($IdLancamentoFinanceiro == null){
				$IdLancamentoFinanceiro = 1;
			} else{
				$IdLancamentoFinanceiro++;
			}
			
			$sql = "INSERT INTO LancamentoFinanceiro SET 
						IdLoja					= '$local_IdLoja',
						IdLancamentoFinanceiro	= '$IdLancamentoFinanceiro', 
						IdContaReceberAgrupado	= '$local_IdContaReceberAgrupador', 
						Valor					= '$ValorParcelaTemp', 
						IdProcessoFinanceiro	= NULL,
						IdStatus				= '1';";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
				
			$sql = "INSERT INTO LancamentoFinanceiroContaReceber SET 
						IdLoja					= '$local_IdLoja',
						IdLancamentoFinanceiro	= '$IdLancamentoFinanceiro', 
						IdContaReceber			= '$IdContaReceberParcela';";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
			
			$sql = "INSERT INTO ContaReceberAgrupadoParcela SET
						IdLoja							= '$local_IdLoja',
						IdContaReceberAgrupador			= '$local_IdContaReceberAgrupador',
						IdContaReceber					= '$IdContaReceberParcela',
						NumParcelaContaReceberAgrupado	= '$i',
						ValorParcela					= '$ValorParcelaTemp',
						ValorDespesas					= '$ValorDespesaTemp',
						DataVencimento					= '$DataVencimentoTemp';";
			$local_transaction[$tr_i] = mysql_query($sql,$con);
			$tr_i++;
		}
		
		for($i=0; $i<$tr_i; $i++){
			if($local_transaction[$i] == false){
				$local_transaction = false;				
			}
		}
		
		if($local_transaction == true){
			$sql = "COMMIT;";
			$local_Erro = 3;			// Mensagem de Inserчуo Positiva
		} else{
			$sql = "ROLLBACK;";
			$local_Erro = 8;			// Mensagem de Inserчуo Negativa
		}
		
		mysql_query($sql,$con);
	}
?>