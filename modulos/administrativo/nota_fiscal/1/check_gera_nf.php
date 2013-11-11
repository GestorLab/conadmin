<?
	$sqlServicoNotaFiscalParametro = "select
										IdNotaFiscalLayoutParametro,
										Valor
									from
										NotaFiscalTipoParametro
									where
										IdLoja = $IdLoja and
										IdNotaFiscalTipo = $lin[IdNotaFiscalTipo] and
										IdNotaFiscalLayout = $lin[IdNotaFiscalLayout]";
	$resServicoNotaFiscalParametro = mysql_query($sqlServicoNotaFiscalParametro,$con);
	while($linServicoNotaFiscalParametro = mysql_fetch_array($resServicoNotaFiscalParametro)){
		$NotaFiscalLayoutParametro[$linServicoNotaFiscalParametro[IdNotaFiscalLayoutParametro]] = $linServicoNotaFiscalParametro[Valor];
	}

	$TipoUtilizacao = $NotaFiscalLayoutParametro[1];
	
#	varifica se tem EV em caso positivo, return false;
	if($NotaFiscalLayoutParametro[7] != 1){
		$sqlVerificarEV = "select 
								count(*) QTD
							from
								LancamentoFinanceiroContaReceber,
								LancamentoFinanceiro 
							where 
								LancamentoFinanceiroContaReceber.IdLoja = $IdLoja and 
								LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and 
								LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and 
								LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and 
								LancamentoFinanceiro.IdContaEventual is not null";
		$resVerificarEV = mysql_query($sqlVerificarEV,$con);
		$linVerificarEV = mysql_fetch_array($resVerificarEV);
		
		if($linVerificarEV[QTD] > 0){
			header("Location: menu_conta_receber_nota_fiscal_check.php?IdAviso=172");
		}
	}
	
#	varifica se tem OS em caso positivo, return false;
	if($NotaFiscalLayoutParametro[8] != 1){
		$sqlVerificarOS = "select 
								count(*) QTD
							from
								LancamentoFinanceiroContaReceber,
								LancamentoFinanceiro 
							where 
								LancamentoFinanceiroContaReceber.IdLoja = $IdLoja and 
								LancamentoFinanceiroContaReceber.IdContaReceber = $IdContaReceber and 
								LancamentoFinanceiroContaReceber.IdLoja = LancamentoFinanceiro.IdLoja and 
								LancamentoFinanceiroContaReceber.IdLancamentoFinanceiro = LancamentoFinanceiro.IdLancamentoFinanceiro and 
								LancamentoFinanceiro.IdOrdemServico is not null";
		$resVerificarOS = mysql_query($sqlVerificarOS,$con);
		$linVerificarOS = mysql_fetch_array($resVerificarOS);
		
		if($linVerificarOS[QTD] > 0){
			header("Location: menu_conta_receber_nota_fiscal_check.php?IdAviso=171");
		}
	}
?>