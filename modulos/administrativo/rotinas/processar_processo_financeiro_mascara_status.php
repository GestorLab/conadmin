<?
	$sql = "select
				ContratoStatus.IdLoja,
				ContratoStatus.IdContrato,
				ContratoStatus.IdMudancaStatus,
				ContratoStatus.DataAlteracaoInicial,
				ContratoStatus.DataAlteracaoFinal,
				LancamentoFinanceiro.IdLancamentoFinanceiro,
				LancamentoFinanceiro.DataReferenciaInicial,
				LancamentoFinanceiro.DataReferenciaFinal,
				ServicoMascaraStatus.PercentualDesconto,
				ServicoMascaraStatus.TaxaMudancaStatus,
				ServicoMascaraStatus.QtdMinimaDia,
				LancamentoFinanceiro.Valor,
				LancamentoFinanceiro.ValorRepasseTerceiro
			from
				LancamentoFinanceiro,
				Contrato,
				(SELECT
					ContratoStatus1.IdLoja,
					ContratoStatus1.IdContrato,
					ContratoStatus1.IdMudancaStatus,
					ContratoStatus1.IdStatusAntigo,
					ContratoStatus1.IdStatus,
					ContratoStatus2.IdStatus IdStatusAtual,
					ContratoStatus2.DataAlteracao DataAlteracaoInicial,
					ContratoStatus1.DataAlteracao DataAlteracaoFinal
				FROM
					ContratoStatus ContratoStatus1,
					ContratoStatus ContratoStatus2
				WHERE
					ContratoStatus1.IdLoja = ContratoStatus2.IdLoja AND
					ContratoStatus1.IdContrato = ContratoStatus2.IdContrato AND
					ContratoStatus1.IdMudancaStatus = (ContratoStatus2.IdMudancaStatus+1)) ContratoStatus,
				ServicoMascaraStatus
			where
				LancamentoFinanceiro.IdLoja = $IdLoja and
				LancamentoFinanceiro.IdLoja = Contrato.IdLoja and
				LancamentoFinanceiro.IdLoja = ContratoStatus.IdLoja and
				LancamentoFinanceiro.IdLoja = ServicoMascaraStatus.IdLoja and
				LancamentoFinanceiro.IdProcessoFinanceiro = $IdProcessoFinanceiro and
				LancamentoFinanceiro.IdContrato = Contrato.IdContrato and
				LancamentoFinanceiro.IdContrato = ContratoStatus.IdContrato and
				Contrato.IdServico = ServicoMascaraStatus.IdServico and

				(
					ContratoStatus.IdStatusAntigo = ServicoMascaraStatus.IdStatus or 
					ContratoStatus.IdStatusAtual = ServicoMascaraStatus.IdStatus
				) and	
				
				ContratoStatus.DataAlteracaoFinal >= LancamentoFinanceiro.DataReferenciaInicial AND
			    
				(
					LancamentoFinanceiro.DataReferenciaFinal > ContratoStatus.DataAlteracaoInicial or 
					LancamentoFinanceiro.DataReferenciaFinal > ContratoStatus.DataAlteracaoFinal
				) and
				
				LancamentoFinanceiro.IdOrdemServico is null and
				LancamentoFinanceiro.IdContaEventual is null and
				LancamentoFinanceiro.Valor > 0";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		// Atualiza as datas para calculo do proporcional
		if(dataConv($lin[DataAlteracaoInicial],'Y-m-d','Ymd') < dataConv($lin[DataReferenciaInicial],'Y-m-d','Ymd')){
			$lin[DataAlteracaoInicial] = $lin[DataReferenciaInicial];
		}

		// Atualiza as datas para calculo do proporcional
		if(dataConv($lin[DataAlteracaoFinal],'Y-m-d','Ymd') > dataConv($lin[DataReferenciaFinal],'Y-m-d','Ymd')){
			$lin[DataAlteracaoFinal] = $lin[DataReferenciaFinal];
		}

		$lin[QtdDiasDesconto]	= nDiasIntervalo($lin[DataAlteracaoInicial], $lin[DataAlteracaoFinal]);
		$lin[QtdDiasLancamento]	= nDiasIntervalo($lin[DataReferenciaInicial], $lin[DataReferenciaFinal]);

		if($lin[PercentualDesconto] > 0 && $lin[QtdDiasDesconto] >= $lin[QtdMinimaDia]){

			$lin[Valor] = $lin[Valor]/$lin[QtdDiasLancamento]*$lin[QtdDiasDesconto]*-1;
			$lin[Valor] = $lin[Valor]*$lin[PercentualDesconto]/100;

			$lin[ValorRepasseTerceiro] = $lin[ValorRepasseTerceiro]/$lin[QtdDiasLancamento]*$lin[QtdDiasDesconto]*-1;
			$lin[ValorRepasseTerceiro] = $lin[ValorRepasseTerceiro]*$lin[PercentualDesconto]/100;
		
			if($lin[Valor] < 0){
								
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
									IdContrato= $lin[IdContrato],
									IdMudancaStatus= $lin[IdMudancaStatus],
									Valor='$lin[Valor]',
									ValorRepasseTerceiro='$lin[ValorRepasseTerceiro]',
									DataReferenciaInicial='$lin[DataAlteracaoInicial]', 
									DataReferenciaFinal='$lin[DataAlteracaoFinal]',
									IdProcessoFinanceiro=$IdProcessoFinanceiro,
									IdStatus = 3";
				$local_transaction[$tr_i]	=	mysql_query($sqlLancamento,$con);
				$tr_i++;

				if($lin[TaxaMudancaStatus] > 0){
					$sqlTaxaMudanca = "SELECT
											COUNT(*) Qtd
										FROM
											LancamentoFinanceiro
										WHERE
											IdLoja = $IdLoja AND
											IdContrato = $lin[IdContrato] AND
											IdMudancaStatus = $lin[IdMudancaStatus]";
					$resTaxaMudanca = mysql_query($sqlTaxaMudanca, $con);
					$linTaxaMudanca = mysql_fetch_array($resTaxaMudanca);

					if($linTaxaMudanca[Qtd] == 1){
						$IdLancamentoFinanceiro++;
						$cont++;
						$sqlLancamento = "INSERT INTO LancamentoFinanceiro SET 
											IdLoja=$IdLoja,
											IdLancamentoFinanceiro=$IdLancamentoFinanceiro,
											IdContrato = $lin[IdContrato],
											IdMudancaStatus = $lin[IdMudancaStatus],
											Valor='$lin[TaxaMudancaStatus]',
											DataReferenciaInicial='$lin[DataAlteracaoInicial]', 
											IdProcessoFinanceiro=$IdProcessoFinanceiro,
											IdStatus = 3";
						$local_transaction[$tr_i]	=	mysql_query($sqlLancamento,$con);
						$tr_i++;
					}
				}
			}
		}
	}
?>