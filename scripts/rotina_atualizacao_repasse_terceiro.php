<?
	include ("../files/conecta.php");
	include ("../files/funcoes.php");

	$sql = "select
				LancamentoFinanceiro.IdLoja,
				LancamentoFinanceiro.IdLancamentoFinanceiro
			from
				LancamentoFinanceiro,
				ContratoVigenciaAtiva
			where
				LancamentoFinanceiro.IdContrato != '' and
				LancamentoFinanceiro.IdLoja = ContratoVigenciaAtiva.IdLoja and
				LancamentoFinanceiro.IdContrato = ContratoVigenciaAtiva.IdContrato and
				ContratoVigenciaAtiva.ValorRepasseTerceiro > 0";
	$res = mysql_query($sql,$con);
	while($lin = mysql_fetch_array($res)){

		$sqlUpdade = "update LancamentoFinanceiro set ValorRepasseTerceiro=Valor where IdLoja='".$lin[IdLoja]."' and IdLancamentoFinanceiro='".$lin[IdLancamentoFinanceiro]."'";
		mysql_query($sqlUpdade,$con);

	}