<?
	$ContNotaFiscal = 0;

	$sqlGeraNotaFiscal = "select
								DISTINCT
								LancamentoFinanceiroDados.IdContaReceber,
								LancamentoFinanceiroDados.IdPessoa
							from
								ProcessoFinanceiro,
								LocalCobranca,
								LancamentoFinanceiroDados,
								Servico,
								Contrato
							where
								ProcessoFinanceiro.IdLoja = $local_IdLoja and
								ProcessoFinanceiro.IdProcessoFinanceiro = $local_IdProcessoFinanceiro and
								ProcessoFinanceiro.IdLoja = LocalCobranca.IdLoja and
								ProcessoFinanceiro.IdLoja = LancamentoFinanceiroDados.IdLoja and
								ProcessoFinanceiro.IdLoja = Servico.IdLoja and
								ProcessoFinanceiro.IdLoja = Contrato.IdLoja and
								ProcessoFinanceiro.Filtro_IdLocalCobranca = LocalCobranca.IdLocalCobranca and
								ProcessoFinanceiro.IdProcessoFinanceiro = LancamentoFinanceiroDados.IdProcessoFinanceiro and
								LancamentoFinanceiroDados.Tipo = 'CO' and
								LancamentoFinanceiroDados.IdServico = Servico.IdServico and
								LancamentoFinanceiroDados.IdContrato = Contrato.IdContrato and
								LocalCobranca.IdNotaFiscalTipo = Servico.IdNotaFiscalTipo";
	$resGeraNotaFiscal = mysql_query($sqlGeraNotaFiscal,$con);
	while($linGeraNotaFiscal = mysql_fetch_array($resGeraNotaFiscal)){
		if($linGeraNotaFiscal[IdContaReceber] != ''){

			$sqlVerificaConfiguracao = "select
											count(*) Qtd
										from
											LancamentoFinanceiroAliquota
										where
											IdLoja = $local_IdLoja and
											IdContaReceber = $linGeraNotaFiscal[IdContaReceber]";
			$resVerificaConfiguracao = mysql_query($sqlVerificaConfiguracao,$con);
			$linVerificaConfiguracao = mysql_fetch_array($resVerificaConfiguracao);

			if($linVerificaConfiguracao[Qtd] > 0){
				$local_transaction[$tr_i] = gera_nf($local_IdLoja, $linGeraNotaFiscal[IdContaReceber]);
				if($local_transaction[$tr_i] == true){
					$ContNotaFiscal++;
				}			
				$tr_i++;
			}

		}
	}
?>