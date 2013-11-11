<?
	include ("../files/conecta.php");

	$sql	=	"START TRANSACTION;";
	mysql_query($sql,$con);
	$tr_i = 0;		

	$sqlContratoVigenciaAtiva = "select
				IdLoja,
				IdContrato,
				Valor,
				ValorDesconto,
				LimiteDesconto
			from
				ContratoVigenciaAtiva
			where
				ValorDesconto > 0 and
				DataTermino is null and
				IdTipoDesconto = 2";
	$resContratoVigenciaAtiva = mysql_query($sqlContratoVigenciaAtiva,$con);
	while($linContratoVigenciaAtiva = mysql_fetch_array($resContratoVigenciaAtiva)){

		$sqlLancamentoFinanceiro = "select
										IdLoja,
										IdLancamentoFinanceiro,
										Valor
									from
										LancamentoFinanceiro
									where
										IdLoja = $linContratoVigenciaAtiva[IdLoja] and
										IdContrato = $linContratoVigenciaAtiva[IdContrato] and
										IdContaEventual is null and
										IdOrdemServico is null and
										ValorDescontoAConceber = 0";
		$resLancamentoFinanceiro = mysql_query($sqlLancamentoFinanceiro,$con);
		while($linLancamentoFinanceiro = mysql_fetch_array($resLancamentoFinanceiro)){

			$PercentualLancamento	= $linLancamentoFinanceiro[Valor] * 100 / $linContratoVigenciaAtiva[Valor];
			$ValorDesconto			= $linContratoVigenciaAtiva[ValorDesconto] * $PercentualLancamento / 100;

#			echo "CO$linContratoVigenciaAtiva[IdContrato] - Valor: $linContratoVigenciaAtiva[Valor] -> Valor Lancamento($linLancamentoFinanceiro[IdLancamentoFinanceiro]): $linLancamentoFinanceiro[Valor] -> Valor do desconto: $ValorDesconto -> Valor Final: ".($linLancamentoFinanceiro[Valor] - $ValorDesconto)."<br>";

			$sql = "update LancamentoFinanceiro set  ValorDescontoAConceber='$ValorDesconto', LimiteDesconto='$linContratoVigenciaAtiva[LimiteDesconto]' where IdLoja='$linContratoVigenciaAtiva[IdLoja]' and IdLancamentoFinanceiro='$linLancamentoFinanceiro[IdLancamentoFinanceiro]'";
			$local_transaction[$tr_i]	=	mysql_query($sql,$con);
			$tr_i++;
		}
	}
		
	for($i=0; $i<$tr_i; $i++){
		if($local_transaction[$i] == false){
			$local_transaction = false;
		}
	}

	if($local_transaction == true || $tr_i==0){
		$sql = "COMMIT;";
		$local_Erro = 51;
	}else{
		$sql = "ROLLBACK;";
		$local_Erro = 50;
	}

	echo $sql;
	mysql_query($sql,$con);
?>