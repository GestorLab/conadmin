<?
	$sqlEncargos = "SELECT
						ContaReceberDados.IdContaReceber,
						ContaReceberRecebimento.IdContaReceberRecebimento,
						ContaReceberRecebimento.DataRecebimento,
						ContaReceberDados.DataVencimento,
						ContaReceberDados.ValorFinal ValorContaReceber,
						LocalCobranca.PercentualJurosDiarios,
						LocalCobranca.PercentualMulta,
						PessoaProcessoFinanceiro.IdContrato
					FROM
						(SELECT
							LancamentoFinanceiroDados.IdPessoa,
							LancamentoFinanceiroDados.IdContrato
						FROM
							LancamentoFinanceiroDados
						WHERE
							LancamentoFinanceiroDados.IdLoja = $local_IdLoja AND
							LancamentoFinanceiroDados.IdProcessoFinanceiro = $local_IdProcessoFinanceiro
						GROUP BY
							LancamentoFinanceiroDados.IdPessoa) PessoaProcessoFinanceiro,
						ContaReceberDados,
						ContaReceberRecebimento,
						LocalCobranca
					WHERE
						PessoaProcessoFinanceiro.IdPessoa = ContaReceberDados.IdPessoa AND
						ContaReceberDados.IdLoja = $local_IdLoja AND
						ContaReceberDados.IdLoja = ContaReceberRecebimento.IdLoja AND
						ContaReceberDados.IdLoja = LocalCobranca.IdLoja AND
						ContaReceberDados.IdContaReceber = ContaReceberRecebimento.IdContaReceber AND
						ContaReceberRecebimento.DataRecebimento > ContaReceberDados.DataVencimento AND
						LocalCobranca.IdLocalCobranca = $local_Filtro_IdLocalCobranca AND
						ContaReceberDados.IdLocalCobranca = LocalCobranca.IdLocalCobranca AND
						ContaReceberDados.IdStatus = 2 AND
						ContaReceberRecebimento.IdStatus = 1 AND
						ContaReceberRecebimento.ValorMoraMulta <= 0 AND
						LocalCobranca.CobrarMultaJurosProximaFatura = 1 and
						ContaReceberDados.IdContaReceber not in (select IdContaReceber from ContaReceberEncargoFinanceiro where IdLoja=$local_IdLoja and IdStatus = 1) and
						PessoaProcessoFinanceiro.IdContrato > 0";
	$resEncargos = mysql_query($sqlEncargos,$con);
	while($linEncargos = mysql_fetch_array($resEncargos)){

		$linEncargos[DataVencimento] = dia_util($linEncargos[DataVencimento]);
		$nDiasIntervalo = nDiasIntervalo($linEncargos[DataVencimento],$linEncargos[DataRecebimento])-1;

		$ValorMulta = $linEncargos[ValorContaReceber] * $linEncargos[PercentualMulta] / 100;
		$ValorJuros = $linEncargos[ValorContaReceber] * $linEncargos[PercentualJurosDiarios] / 100 * $nDiasIntervalo;

		$ValorMultaJuros = $ValorMulta + $ValorJuros;

		if($ValorMultaJuros > 0){
		
			$sqlEncargoFinanceiro = "select
										max(IdEncargoFinanceiro) IdEncargoFinanceiro
									from
										ContaReceberEncargoFinanceiro
									where
										IdLoja=$local_IdLoja";

			$resEncargoFinanceiro = mysql_query($sqlEncargoFinanceiro);
			$linEncargoFinanceiro = mysql_fetch_array($resEncargoFinanceiro);
			
			$IdEncargoFinanceiro = $linEncargoFinanceiro[IdEncargoFinanceiro];
			if($IdEncargoFinanceiro == null){
				$IdEncargoFinanceiro = 1;
			}else{
				$IdEncargoFinanceiro++;
			}

			$sql = "INSERT INTO ContaReceberEncargoFinanceiro set
						IdLoja = $local_IdLoja,
						IdEncargoFinanceiro = $IdEncargoFinanceiro,
						IdContaReceber = $linEncargos[IdContaReceber],
						IdContaReceberRecebimento = $linEncargos[IdContaReceberRecebimento],
						ValorMulta = '$ValorMulta',
						ValorJurosDiarios = '$ValorJuros',
						IdStatus = 1,
						DataCriacao = CONCAT(CURDATE(),' ',CURTIME())";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
								
			$sqlLancamento = "select
								max(IdLancamentoFinanceiro) IdLancamentoFinanceiro
							from
								LancamentoFinanceiro
							where
								IdLoja=$IdLoja";
			$resLancamento = mysql_query($sqlLancamento);
			$linLancamento = mysql_fetch_array($resLancamento);
			
			$IdLancamentoFinanceiro = $linLancamento[IdLancamentoFinanceiro];
			if($IdLancamentoFinanceiro == null){
				$IdLancamentoFinanceiro = 1;
			}else{
				$IdLancamentoFinanceiro++;
			}

			$cont++;
			$sqlLancamento = "INSERT INTO LancamentoFinanceiro SET 
									IdLoja=$IdLoja,
									IdLancamentoFinanceiro=$IdLancamentoFinanceiro,
									IdEncargoFinanceiro=$IdEncargoFinanceiro,
									IdContrato = $linEncargos[IdContrato],
									Valor='$ValorMultaJuros',
									IdProcessoFinanceiro=$IdProcessoFinanceiro,
									IdStatus = 3";
			$local_transaction[$tr_i]	=	mysql_query($sqlLancamento,$con);
			$tr_i++;
		}
	}
?>